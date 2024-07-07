<?php

namespace App\Services\User;

use App\Exceptions\AppError;
use App\Models\User;

class GetUserService {
    public function execute(int $id) {
        $user = User::find($id);

        if (!boolval($user)) {
            throw new AppError('Não encontrado', 404);
        }

        return $user;
    }
}
