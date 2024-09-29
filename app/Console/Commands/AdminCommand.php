<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AdminCommand extends Command {
    protected $signature = 'admin:create';
    protected $description = 'Crete a new admin';

    public function handle() {
        User::create([
            'name' => 'admin',
            'email' => 'adm@mail.com',
            'type' => 'admin',
            'password' => '12345678',
        ]);
        $this->info('Admin successfully created');
    }

}
