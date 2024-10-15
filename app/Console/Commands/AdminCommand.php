<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AdminCommand extends Command {
    protected $signature = 'admin:create {email=adm@mail.com}';
    protected $description = 'Crete a new admin';

    public function handle() {
        User::create([
            'name' => 'admin',
            'email' => $this->argument('email'),
            'type' => 'admin',
            'password' => $this->secret('What is the password?'),
        ]);
        $this->info('Admin successfully created');
    }

}
