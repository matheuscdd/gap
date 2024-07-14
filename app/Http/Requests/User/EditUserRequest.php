<?php

namespace App\Http\Requests\User;

use App\Models\User;

class EditUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isTimeNotExpired(app(User::class), $this->route('id'));
    }

    public function rules(): array {
        return $this->getRules('name', 'email');
    }
}
