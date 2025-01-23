<?php

namespace App\Models;

use App\Constraints\TruckKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model {
    use HasFactory;

    protected $table = Keys::TABLE;

    protected $fillable = [
        Keys::PLATE,
        Keys::AXIS,
        Keys::PHOTO,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];
}

