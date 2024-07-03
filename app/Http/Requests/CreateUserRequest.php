<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest {
    private const EMAIL_MIN = 8;
    private const EMAIL_MAX = 255;
    private const NAME_MIN = 3;
    private const NAME_MAX = 64;
    private const PASSWD_MIN = 8;
    private const PASSWD_MAX = 64;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        # TODO adicionar as validações de tamanho de campos

        return [
            'email' => ['required', 'email', 'unique:users', 'min:' . $this::EMAIL_MIN, 'max:' . $this::EMAIL_MAX],
            'name' => ['required', 'min:' . $this::NAME_MIN, 'max:' . $this::NAME_MAX],
            'password' => ['required', 'min:' . $this::PASSWD_MIN, 'max:' . $this::PASSWD_MAX],
        ];
    }

    private function getMessageSize(string $type, string $field, int $val) {
        if ($type === 'max') {
            return "O campo $field não contém o mínimo de $val caracteres";
        }
        return "O campo $field não contém o mínimo de $val caracteres";
    }

    public function messages(): array {
        return [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um endereço válido',
            'email.unique' => 'O campo email já está em uso',
            'email.min' => $this->getMessageSize('min', 'email', $this::EMAIL_MIN),
            'email.max' => $this->getMessageSize('max', 'email', $this::EMAIL_MAX),
            'name.required' => 'O campo de nome é obrigatório',
            'name.min' => $this->getMessageSize('min', 'nome', $this::NAME_MIN),
            'name.max' => $this->getMessageSize('max', 'nome', $this::NAME_MAX),
            'password.required' => 'A senha é obrigatória',
            'password.min' => $this->getMessageSize('min', 'senha', $this::PASSWD_MIN),
            'password.max' => $this->getMessageSize('max', 'senha', $this::PASSWD_MAX),
        ];
    }
}
