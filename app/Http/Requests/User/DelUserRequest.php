<?php

namespace App\Http\Requests\User;


class DelUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isAdmin();
    }
}
