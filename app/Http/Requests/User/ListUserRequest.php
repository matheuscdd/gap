<?php

namespace App\Http\Requests\User;


class ListUserRequest extends UserRequest {
    public function authorize(): bool {
        return $this->isAdmin();
    }
}
