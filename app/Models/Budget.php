<?php

namespace App\Models;

use App\Constraints\BudgetKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model {
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
        Keys::REVENUE,
        Keys::COST,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];
}

