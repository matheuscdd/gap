<?php

namespace App\Services\User;

use App\Models\User;

class CreateUserService {
    public function execute(array $data) {
        return User::create($data);
    }
}
