<?php

namespace App\Http\Services\User;

use App\Models\User;
use App\Constraints\UserKeysConstraints as Keys;
use Illuminate\Support\Facades\Log;

class UserService {
    public static function create(array $data) {
        $data[Keys::EMAIL] = strtolower($data[Keys::EMAIL]);
        return User::create($data);
    }

    public static function find(int $id) {
        return User::find($id);
    }

    public static function edit(int $id, array $data) {
        if (array_key_exists(Keys::EMAIL, $data)) {
            $data[Keys::EMAIL] = strtolower($data[Keys::EMAIL]);
        }

        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public static function del(int $id) {
        $user = User::find($id);
        $user->active = false;
        $user->save();
        return response(null, 204);
    }

    public static function list() {
        return User::all();
    }
}
