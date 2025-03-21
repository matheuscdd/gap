<?php

namespace App\Models;

use App\Constraints\DriverKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model {
    use HasFactory;

    protected $table = Keys::TABLE;

    protected $fillable = [
        Keys::NAME,
        Keys::CPF,
        Keys::PHOTO,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];

    public function deliveries() {
        return $this->hasMany(Delivery::class, 'driver', 'id');
    }
}

