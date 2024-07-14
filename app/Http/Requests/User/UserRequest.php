<?php

namespace App\Http\Requests\User;

use App\Enums\TypeUser;
use App\Http\Requests\Request;
use App\Utils\Utils;
use Exception;
use Illuminate\Validation\Rules\Enum;

class UserRequest extends Request {
    protected const EMAIL_MIN = 8;
    protected const EMAIL_MAX = 255;
    protected const NAME_MIN = 3;
    protected const NAME_MAX = 64;
    protected const PASSWD_MIN = 8;
    protected const PASSWD_MAX = 64;

    protected function getRules(...$keys): array {
        $ref = [
            'email' => ['required', 'email', 'unique:users', 'min:' . $this::EMAIL_MIN, 'max:' . $this::EMAIL_MAX],
            'name' => ['required', 'min:' . $this::NAME_MIN, 'max:' . $this::NAME_MAX],
            'password' => ['required', 'min:' . $this::PASSWD_MIN, 'max:' . $this::PASSWD_MAX],
            'type' => ['required', new Enum(TypeUser::class)],
        ];
        $rules = array_filter($ref, fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
        if (count($rules) === 0) {
            throw new Exception('getRules: No key found');
        }
        return $rules;
    }

    public function messages(): array {
        return Utils::arrayFlat([
            $this->getMsgEnum('type', TypeUser::class),
            'type.required' => $this->getMsgRequired('tipo de usuário'),
            'email.required' => $this->getMsgRequired('email'),
            'email.email' => $this->getMsgValid('email'),
            'email.unique' => $this->getMsgUnique('email'),
            'email.min' => $this->getMsgSizeMin('email', $this::EMAIL_MIN),
            'email.max' => $this->getMsgSizeMax('email', $this::EMAIL_MAX),
            'name.required' => $this->getMsgRequired('nome'),
            'name.min' => $this->getMsgSizeMin('nome', $this::NAME_MIN),
            'name.max' => $this->getMsgSizeMax('nome', $this::NAME_MAX),
            'password.required' => $this->getMsgRequired('senha'),
            'password.min' => $this->getMsgSizeMin('senha', $this::PASSWD_MIN),
            'password.max' => $this->getMsgSizeMax('senha', $this::PASSWD_MAX),
        ]);
    }
}

