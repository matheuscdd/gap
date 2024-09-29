<?php

namespace App\Http\Requests\User;

use App\Models\User;
use App\Constraints\UserKeysConstraints as Keys;

class EditUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isTimeNotExpired(app(User::class), $this->route('id'));
    }

    public function rules(): array {
        return $this->getRules(true, Keys::NAME, Keys::EMAIL);
    }
}
