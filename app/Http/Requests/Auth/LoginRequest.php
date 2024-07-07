<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request {
    public function rules(): array {
        return [
            'email' => ['required'],
            'password' => ['required']
        ];
    }

    public function messages(): array {
        return [
            'email.required' => $this->getMsgRequired('email'),
            'password.required' => $this->getMsgRequired('senha'),
        ];
    }
}
