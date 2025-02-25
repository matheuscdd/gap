<?php

namespace App\Constraints;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use \Illuminate\Validation\Rule;

class ValidatorConstraints {
    public const REQUIRED = 'required';
    public const MIN = 'min';
    public const MAX = 'max';
    public const UNIQUE = 'unique';
    public const EMAIL = 'email';
    public const DIGITS = 'digits';
    public const DATE_FORMAT = 'date_format';
    public const DATE_SCHEMA = 'Y-m-d';
    public const REGEX = 'regex';
    public const NOT_REGEX = 'not_regex';
    public const ARRAY = 'array';
    public const NUMERIC = 'numeric';
    public const INTEGER = 'integer';
    public const NULLABLE = 'nullable';
    public const ONLY_NUMBERS = '/^[\d]+$/';
    public const ALPHANUM = 'alpha_num';

    private static function dot($field, $key): string {
        return "$field.$key";
    }

    private static function colon($field, $key): string {
        return "$key:$field";
    }

    public static function cDecimal(): string {
        return self::cRegex('/^\d{1,20}$|(?=^.{1,20}$)^\d+\.\d{0,2}$/i');
    }

    public static function cRegex($field): string {
        return self::colon($field, self::REGEX);
    }

    public static function cNotRegex($field): string {
        return self::colon($field, self::NOT_REGEX);
    }

    public static function cDate(): string {
        return self::colon(self::DATE_SCHEMA, self::DATE_FORMAT);
    }

    public static function dMax($field): string {
        return self::dot($field, self::MAX);
    }

    public static function dMin($field): string {
        return self::dot($field, self::MIN);
    }

    public static function dUnique($field): string {
        return self::dot($field, self::UNIQUE);
    }

    public static function dEmail($field): string {
        return self::dot($field, self::EMAIL);
    }

    public static function dRequired($field): string {
        return self::dot($field, self::REQUIRED);
    }

    public static function dDigits($field): string {
        return self::dot($field, self::DIGITS);
    }

    public static function cMax($field): string {
        return self::colon($field, self::MAX);
    }

    public static function cMin($field): string {
        return self::colon($field, self::MIN);
    }

    public static function unique($table, $id = null): string {
        return Rule::unique($table)->ignore($id);
    }

    public static function cEmail($field): string {
        return self::colon($field, self::EMAIL);
    }

    public static function cRequired($field): string {
        return self::colon($field, self::REQUIRED);
    }

    public static function cDigits($field): string {
        return self::colon($field, self::DIGITS);
    }

    public static function uppercase(): Uppercase {
        return new Uppercase;
    }
}

class Uppercase implements ValidationRule {
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (strtoupper($value) === $value) return;
        $fail("O atributo precisa estar em letras maiúsculas.");
    }
}