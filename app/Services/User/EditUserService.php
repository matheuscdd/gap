<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class EditUserService {
    public function execute(int $id, array $data) {
        Log::debug($data);
        $user = User::find($id);
        $user->update($data);
        $user->save();
        Log::debug($user);
        return $user;
    }
}
