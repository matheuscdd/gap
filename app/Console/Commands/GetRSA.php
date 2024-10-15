<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetRSA extends Command {
    protected $signature = 'rsa:gen';
    protected $description = 'Generate keys rsa';

    public function handle() {
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        openssl_pkey_export($privateKey, $privateKeyPem);
        $keyDetails = openssl_pkey_get_details($privateKey);
        $publicKeyPem = $keyDetails["key"];

        $this->warn("Don't share these info with anyone\n");
        $this->info("$privateKeyPem\n");
        $this->line($publicKeyPem);
    }

}
