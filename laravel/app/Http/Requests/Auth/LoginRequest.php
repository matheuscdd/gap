<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Constraints\UserKeysConstraints as Keys;
use App\Constraints\ValidatorConstraints as Schema;
use App\Constraints\UserTransConstraints as Trans;

class LoginRequest extends Request {
    public function rules(): array {
        return [
            Keys::EMAIL => [Schema::REQUIRED],
            Keys::PASSWORD => [Schema::REQUIRED]
        ];
    }

    public function messages(): array {
        return [
            Schema::dRequired(Keys::EMAIL) => $this->getMsgRequired(Trans::EMAIL),
            Schema::dRequired(Keys::PASSWORD) => $this->getMsgRequired(Trans::PASSWORD),
        ];
    }
}
