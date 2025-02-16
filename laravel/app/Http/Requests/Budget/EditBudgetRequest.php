<?php

namespace App\Http\Requests\Budget;

use App\Http\Requests\Request;
use Exception;
use App\Constraints\BudgetKeysConstraints as Keys;
use App\Constraints\ClientKeysConstraints as Client;
use App\Constraints\StockTypeKeysConstraints as StockType;
use App\Constraints\BudgetTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Enums\{Unloaded, PaymentMethod, PaymentStatus};

class EditBudgetRequest extends BudgetRequest {
    public function rules(): array {
        return $this->getRules(true, false, ...Keys::ALL);
    }
}

