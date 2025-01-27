<?php

namespace App\Models;

use App\Constraints\DeliveryKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model {
    use HasFactory;

    protected $table = Keys::TABLE;

    protected $fillable = [
        Keys::CLIENT,
        Keys::DELIVERY_DATE,
        Keys::DELIVERY_ADDRESS,
        Keys::PROVIDER_NAME,
        Keys::PROVIDER_CITY,
        Keys::UNLOADED,
        Keys::PAYMENT_STATUS,
        Keys::PAYMENT_METHOD,
        Keys::PAYMENT_DATE,
        Keys::RECEIPT_DATE,
        Keys::DRIVER,
        Keys::INVOICE,
        Keys::TRAVEL_COST,
        Keys::UNLOADING_COST,
        Keys::REVENUE,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];

    protected $casts = [
        Keys::TRAVEL_COST => 'float',
        Keys::UNLOADING_COST => 'float',
        Keys::REVENUE => 'float',
    ];
}

