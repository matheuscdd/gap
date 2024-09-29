<?php

namespace App\Models;

use App\Constraints\StockTypeKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockType extends Model {
    use HasFactory;

    protected $table = 'stock_type';

    protected $fillable = [
        Keys::NAME,
    ];
}

