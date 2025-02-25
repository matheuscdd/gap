<?php

namespace App\Models;

use App\Constraints\StocksKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStock extends Model {
    use HasFactory;

    protected $table = 'deliveries_stocks';

    protected $fillable = [
        Keys::DELIVERY,
        Keys::STOCK,
    ];
}

