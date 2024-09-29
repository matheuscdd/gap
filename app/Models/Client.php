<?php

namespace App\Models;

use App\Constraints\ClientKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        Keys::NAME,
        Keys::CNPJ,
        Keys::CEP,
        Keys::ADDRESS,
        Keys::CELLPHONE,
        Keys::CREATED_BY,
        Keys::UPDATED_BY,
    ];
}

