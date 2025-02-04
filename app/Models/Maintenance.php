<?php

namespace App\Models;

use App\Constraints\MaintenanceKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model {
    use HasFactory;

    protected $table = Keys::TABLE;

    protected $fillable = [
        Keys::SERVICE,
        Keys::KM,
        Keys::COST,
        Keys::DATE,
        Keys::TRUCK,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];

    protected $casts = [
        Keys::COST => 'float'
    ];
}

