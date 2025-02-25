<?php

namespace App\Constraints;

class UserKeysConstraints {
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const ACTIVE = 'active';
    public const TYPE = 'type';
    public const TOKEN = 'token';
    public const TABLE = 'users';
    public const ALL = [self::NAME, self::EMAIL, self::PASSWORD, self::TYPE];
}
