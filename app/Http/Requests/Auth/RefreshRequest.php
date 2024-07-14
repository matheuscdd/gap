<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class RefreshRequest extends Request {
    public function rules():array {
        return [
            'token' => ['required']
        ];
    }

    public function messages(): array {
        return [
            'token.required' => $this->getMsgRequired('token'),
        ];
    }
}
