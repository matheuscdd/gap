<?php

namespace App\Models;

use App\Constraints\StocksKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model {
    use HasFactory;

    protected $table = Keys::TABLE;

    protected $fillable = [
        Keys::NAME,
        Keys::WEIGHT,
        Keys::QUANTITY,
        Keys::EXTRA,
        Keys::TYPE,
    ];
}

