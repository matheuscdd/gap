<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Phar;
use PharData;
use DateTime;

class DumpDatabase extends Command {
    protected $signature = 'db:dump';
    protected $description = 'Dump entire database';
    private const FOLDER = 'backups/';

    public function handle() {
        $sqlDump = $this->getSqlDump();
        if (!$sqlDump) return;
        $this->sendGitHub($sqlDump);
    }

    private function getSqlDump() {
        $db = env('POSTGRES_DB');
        $user = env('POSTGRES_USER');
        $port = env('DB_PORT');
        $content = $this->execSys("pg_dump -Z 9 --data-only -h pgsql -p $port -U $user -d $db");
        if (is_null($content)) return;
        $content = base64_encode($content);
        $integrityCode1 = base64_encode($this->getHashSha256($content));
        $contents = join('|', 
            array_map([$this, 'encryptRSA'], str_split($content, 50))
        );
        $rsaStep = "$integrityCode1%$contents";
        $rawDataAES256CBC = $this->encryptAES256CBC($rsaStep);
        $integrityCode2 = $this->getHashSha256($rawDataAES256CBC['content']);
        $AES256CBCStep = base64_encode($rawDataAES256CBC['iv'].$integrityCode2.$rawDataAES256CBC['content']);
        return $AES256CBCStep;
    }

    private function getHashSha256($content) {
        return hash_hmac('sha256', $content, env('PRIVATE_KEY_256'), true);
    }

    private function execSys($cmd) {
        return shell_exec($cmd) ?? $this->error("Something wen't wrong");
    }
    
    private function encryptRSA($decryptedContent) {
        $key = str_replace('\n', PHP_EOL, base64_decode(env('PUBLIC_KEY_RSA_B64')));
        openssl_public_encrypt($decryptedContent, $encryptedContent, $key);
        return base64_encode($encryptedContent);
    }

    private function encryptAES256CBC($content) {
        $key = env('PRIVATE_KEY_256');
        $ivlen = openssl_cipher_iv_length($cipher='AES-256-CBC');
        $iv = random_bytes($ivlen);
        $encryptedContent = openssl_encrypt($content, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return [
            'iv' => $iv,
            'content' => $encryptedContent,
        ];
    }

    private function compressTarGz($content) {
        $tarFilename = self::FOLDER.time().'.tar';
        $tar = new PharData($tarFilename);
        $tar->addFromString('content', $content);
        $tar->compress(Phar::GZ);
        $tarGzFilename = $tarFilename.'.gz';
        $data = file_get_contents($tarGzFilename);
        unlink($tarFilename);
        unlink($tarGzFilename);
        return $data;
    }

    private function sendGitHub($sqlDump) {
        $b64TarGz = base64_encode($this->compressTarGz($sqlDump));
        $data = json_encode([
            'message' => (string) time(),
            'committer' => [
                'name' => 'bot',
                'email' => env('EMAIL_SUPPORT')
            ],
            'content' => $b64TarGz,
        ]);
        $dataLen = strlen($data);
        $user = env('GITHUB_BUCKET_USER');
        $rep = env('GITHUB_BUCKET_REP');
        $token = env('PRIVATE_KEY_GITHUB');
        $format = '.tar.gz';
        $path = date("Y/m/d/G/").time().$format;
        $url = "https://api.github.com/repos/$user/$rep/contents/$path";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            "Content-Length: $dataLen",
            'User-Agent: gapapi/1.0',
            "Authorization: Bearer $token",
            'Accept: */*',
            'Host: api.github.com',
        ]);
        
        $res = curl_exec($ch);
        curl_close($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode !== 201) {
            return $this->error($res);
        }
        $date = new DateTime('now');
        $date = $date->format('c');
        $this->info("$date - Backup saved successfully");
    }
}
