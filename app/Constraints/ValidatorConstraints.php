<?php

namespace App\Constraints;

class ValidatorConstraints {
    public const REQUIRED = 'required';
    public const MIN = 'min';
    public const MAX = 'max';
    public const UNIQUE = 'unique';
    public const EMAIL = 'email';

    private static function dot($field, $key): string {
        return "$field.$key";
    }

    private static function colon($field, $key): string {
        return "$key:$field";
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

    public static function cMax($field): string {
        return self::colon($field, self::MAX);
    }

    public static function cMin($field): string {
        return self::colon($field, self::MIN);
    }

    public static function cUnique($field): string {
        return self::colon($field, self::UNIQUE);
    }

    public static function cEmail($field): string {
        return self::colon($field, self::EMAIL);
    }

    public static function cRequired($field): string {
        return self::colon($field, self::REQUIRED);
    }
}