<?php

namespace App\Constraints;

class DriverKeysConstraints {
    public const NAME = 'name';
    public const CPF = 'CPF';
    public const PHOTO = 'photo';
    public const CREATED_BY = 'created_by';
    public const UPDATED_BY = 'updated_by';
    public const TABLE = 'drivers';
    public const ALL = [self::NAME, self::CPF, self::PHOTO];
}
