<?php

namespace App\Http\Requests\User;

use App\Constraints\UserKeysConstraints as Keys;

class CreateUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isAdmin();
    }

    public function rules(): array {
        $userRequest = new UserRequest();
        return $userRequest->getRules(false, ...Keys::ALL);
    }
}
