<?php

namespace App\Http\Services\User;

use App\Models\User;
use App\Constraints\UserKeysConstraints as Keys;
use App\Exceptions\AppError;
use Illuminate\Support\Facades\Log;

class UserService {
    public static function create(array $data) {
        $data[Keys::EMAIL] = strtolower($data[Keys::EMAIL]);
        return response(User::create($data), 201);
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
        if (auth()->user()->id === $id) {
            throw new AppError("O usuário não pode excluir a si mesmo", 403);
        }
        $user = User::find($id);
        $user->active = false;
        $user->save();
        return response(null, 204);
    }

    public static function list() {
        return User::where(Keys::ACTIVE, '=', true)->get();
    }
}
