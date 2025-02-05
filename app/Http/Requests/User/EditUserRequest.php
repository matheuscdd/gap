<?php

namespace App\Http\Requests\User;

use App\Constraints\UserKeysConstraints as Keys;

class EditUserRequest extends UserRequest {
    public function rules(): array {
        return $this->getRules(true, false, Keys::NAME, Keys::EMAIL);
    }
}
