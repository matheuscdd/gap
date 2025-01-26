<?php

namespace App\Constraints;

class MaintenanceKeysConstraints {
    public const SERVICE = 'service';
    public const KM = 'km';
    public const COST = 'cost';
    public const DATE = 'date';
    public const TRUCK = 'truck';
    public const CREATED_BY = 'created_by';
    public const UPDATED_BY = 'updated_by';
    public const TABLE = 'maintenances';
    public const ALL = [self::SERVICE, self::KM, self::COST, self::DATE, self::TRUCK];
}
