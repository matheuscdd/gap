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

class BudgetRequest extends Request {
    protected const DELIVERY_ADDRESS_MAX = 256;
    protected const DELIVERY_ADDRESS_MIN = 3;
    protected const PROVIDER_NAME_MAX = 128;
    protected const PROVIDER_NAME_MIN = 3;
    protected const PROVIDER_CITY_MAX = 128;
    protected const PROVIDER_CITY_MIN = 2;
    protected const FLOAT_CASES = 2;
    

    public function getRules($partial, $keepRequired, ...$keys): array {
        $ref = [
            Keys::DELIVERY_DATE => [
                Schema::REQUIRED,
                Schema::cDate(Keys::DATE_FORMAT)
            ],
            Keys::PAYMENT_DATE => [
                Schema::cDate(Keys::DATE_FORMAT)
            ],
            Keys::CLIENT => [
                Schema::REQUIRED,
                Rule::exists(Client::TABLE, 'id')
            ],
            Keys::DELIVERY_ADDRESS => [
                Schema::REQUIRED,
                Schema::cMin(self::DELIVERY_ADDRESS_MIN),
                Schema::cMax(self::DELIVERY_ADDRESS_MAX),
            ],
            Keys::PROVIDER_NAME => [
                Schema::REQUIRED,
                Schema::cMin(self::DELIVERY_ADDRESS_MIN),
                Schema::cMax(self::DELIVERY_ADDRESS_MAX),
            ],
            Keys::PROVIDER_CITY => [
                Schema::REQUIRED,
                Schema::cMin(self::PROVIDER_CITY_MIN),
                Schema::cMax(self::PROVIDER_CITY_MAX),
            ],
            Keys::REVENUE => [
                Schema::REQUIRED,
                Schema::cDecimal(),
            ],
            Keys::COST => [
                Schema::REQUIRED,
                Schema::cDecimal(),
            ],
            Keys::UNLOADED => [
                Schema::REQUIRED,
                new Enum(Unloaded::class)
            ],
            Keys::PAYMENT_METHOD => [
                Schema::REQUIRED,
                new Enum(PaymentMethod::class)
            ],
            Keys::PAYMENT_STATUS => [
                Schema::REQUIRED,
                new Enum(PaymentStatus::class)
            ],
            Keys::STOCKS => [
                Schema::REQUIRED
            ],
            
        ];

        $rules = self::retrieveRules($partial, $ref, $keys, $keepRequired);
        if (count($rules) === 0) {
            throw new Exception('getRules: No key found');
        }
        return $rules;
    }
}

