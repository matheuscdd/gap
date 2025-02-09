<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PharData;


class LoadDatabase extends Command {
    protected $signature = 'db:load {filename}';
    protected $description = 'Dump entire database';
    private const FOLDER = 'backups/';

    public function handle() {
        if (env('APP_ENV') !== 'dev') return $this->error('Uploading is only available in development environment');
        $filename = self::FOLDER.$this->argument('filename');
        $this->decryptSqlDump($filename);        
    }

    private function decryptSqlDump($filename) {
        $AES256CBCStep = base64_decode($this->decompressTagGz($filename));
        $rsaStep = $this->decryptAES256CBC($AES256CBCStep);
        if (!$rsaStep) return $this->error('Invalid file');
        [$integrityCode1, $encryptedContents] = explode('%', $rsaStep);
        $integrityCode1 = base64_decode($integrityCode1);
        $encryptedContents = explode('|', $encryptedContents);
        $decryptedContents =  join('', array_map([$this, 'decryptRSA'], $encryptedContents));
        $calcmac = $this->getHashSha256($decryptedContents);
        if (!hash_equals($integrityCode1, $calcmac)) return $this->error('Invalid hash');
        $sqlContent = gzdecode(base64_decode($decryptedContents));
        $this->insertSql($sqlContent);
    }

    private function insertSql($content) {
        $db = env('POSTGRES_DB');
        $user = env('POSTGRES_USER');
        $port = env('DB_PORT');
        $filename = self::FOLDER.'script.sql';
        $this->makeFile($content, $filename);
        $res = $this->execSys("psql -h pgsql -p $port -U $user -d $db -f $filename");
        $this->line($res);
        unlink($filename);
    }

    private function makeFile($content, $name) {
        $f = fopen($name, 'w');
        fwrite($f, $content);
        fclose($f);
    }

    private function decryptRSA($encryptedContent) {
        $key = str_replace('\n', PHP_EOL, base64_decode(env('PRIVATE_KEY_RSA_B64')));
        openssl_private_decrypt(base64_decode($encryptedContent), $decryptedContent, $key);
        return $decryptedContent;
    }

    private function decryptAES256CBC($rawData) {
        $key = env('PRIVATE_KEY_256');
        $ivlen = openssl_cipher_iv_length($cipher='AES-256-CBC');
        $iv = substr($rawData, 0, $ivlen);
        $hmac = substr($rawData, $ivlen, $sha2len=32);
        $encryptedContent = substr($rawData, $ivlen+$sha2len);
        $decryptedContent = openssl_decrypt($encryptedContent, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $decryptedContent, $key, true);
        if (!hash_equals($hmac, $calcmac)) return null;
        return $decryptedContent;
    }

    private function getHashSha256($content) {
        return hash_hmac('sha256', $content, env('PRIVATE_KEY_256'), true);
    }

    private function execSys($cmd) {
        return shell_exec($cmd) ?? $this->error("Something wen't wrong");
    }
    
    private function decompressTagGz($tarGzFilename) {
        $tarFilename = str_replace('.gz', '', $tarGzFilename);
        $contentFilename = self::FOLDER.'content';
        $tarGz = new PharData($tarGzFilename);
        $tarGz->decompress();
        $tar = new PharData($tarFilename);
        $tar->extractTo(self::FOLDER);
        $data = file_get_contents($contentFilename);
        unlink($tarFilename);
        unlink($contentFilename);
        return $data;
    }
}
