<?php

namespace App\Http\Requests\User;

use App\Constraints\UserKeysConstraints as Keys;
use App\Constraints\ValidatorConstraints as Schema;

class ResetPasswordRequest extends UserRequest {
    public function rules(): array {
        return array_merge($this->getRules(true, true, Keys::PASSWORD), ['key' => [Schema::REQUIRED]]);
    }
}
