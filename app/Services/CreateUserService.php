<?php

namespace App\Services;

use App\Exceptions\AppError;
use App\Models\User;

class CreateUserService {
    public function execute(array $data) {
        $existsUser = User::firstWhere('email', $data['email']);

        // if (!is_null($existsUser)) {
        //     throw new AppError('Email já cadastrado', 409);
        // }
        
        return User::create($data);
    }
}
