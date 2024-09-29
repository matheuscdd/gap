<?php

namespace App\Models;

use App\Constraints\BudgetKeysConstraints as Keys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetStock extends Model {
    use HasFactory;

    protected $table = 'budgets_stocks';

    protected $fillable = [
        Keys::BUDGET,
        Keys::STOCK,
    ];
}

