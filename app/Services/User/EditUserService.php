<?php

namespace App\Services\User;

use App\Models\User;

class EditUserService {
    public function execute(int $id, array $data) {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }
}
