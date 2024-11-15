<?php

namespace App\Http\Requests;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use App\Constraints\ValidatorConstraints as Schema;

class Request extends FormRequest {
    protected const MAX_HOURS = 1;

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [];
    }

    protected function isAdmin(): bool {
        return auth()->user()->type === 'admin';
    }

    protected function isTimeNotExpired(Model $model, int $id): bool {
        return $model::find($id)->created_at->diffInHours(new DateTime()) < $this::MAX_HOURS;
    }

    protected function getMsgSizeMax(string $field, int $val): string {
        return "O campo $field contém mais caracteres do que o permitido de $val";
    }

    protected function getMsgSizeMin(string $field, int $val): string {
        return "O campo $field não contém o mínimo de $val caracteres";
    }

    protected function getMsgRequired(string $field): string {
        return "O campo $field é obrigatório";
    }

    protected function getMsgUnique(string $field): string {
        return "O campo $field já está em uso";
    }

    protected function getMsgValid(string $field): string {
        return "O campo $field deve ser válido";
    }

    protected function getMsgSize(string $field, int $val): string {
        return "O campo $field deve ter o exato tamanho de dígitos de $val";
    }

    protected function getMsgEnum(string $column, $enum): array {
        $key = "$column.Illuminate\Validation\Rules\Enum";

        $rawCases = [];
        foreach ($enum::cases() as $prop) {
            array_push($rawCases, "'$prop->value'");
        }
        $handleCases = join(', ', $rawCases);

        return [$key => "Seleção inválida, as opções são [$handleCases]"];
    }

    protected function retrieveRules(bool $partial, array $ref, array $keys, bool $keepRequired): array {
        if (!$partial) return $ref;
        $handle = array_filter($ref, fn($el) => in_array($el, $keys), ARRAY_FILTER_USE_KEY);
        if ($keepRequired) return $handle;
        foreach($handle as $key => $value) {
            $handle[$key] = array_filter($value, fn($el) => $el !== Schema::REQUIRED);
        }
        return $handle;
    }
    
}
