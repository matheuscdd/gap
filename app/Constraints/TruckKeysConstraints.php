<?php

namespace App\Constraints;

class TruckKeysConstraints {
    public const PLATE = 'plate';
    public const PHOTO = 'photo';
    public const AXIS = 'axis';
    public const CREATED_BY = 'created_by';
    public const UPDATED_BY = 'updated_by';
    public const TABLE = 'trucks';
    public const ALL = [self::PLATE, self::PHOTO, self::AXIS];
}
