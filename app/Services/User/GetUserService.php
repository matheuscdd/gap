<?php

namespace App\Services\User;

use App\Models\User;

class GetUserService {
    public function execute(int $id) {
        $user = User::find($id);
        return $user;
    }
}
