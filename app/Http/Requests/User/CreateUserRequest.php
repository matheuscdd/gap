<?php

namespace App\Http\Requests\User;

class CreateUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isAdmin();
    }

    public function rules(): array {
        return $this->getRules('name', 'email', 'password', 'type');
    }
}
