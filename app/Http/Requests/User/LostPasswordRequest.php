<?php

namespace App\Http\Requests\User;

use App\Constraints\UserKeysConstraints as Keys;
use App\Constraints\ValidatorConstraints as Schema;

class LostPasswordRequest extends UserRequest {
    public function rules(): array {
        return [Keys::EMAIL => [Schema::REQUIRED]];
    }
}
