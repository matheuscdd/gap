<?php

namespace App\Http\Requests\Maintenance;

use App\Http\Requests\Request;
use Exception;
use App\Constraints\MaintenanceKeysConstraints as Keys;
use App\Constraints\MaintenanceTransConstraints as Trans;
use App\Constraints\TruckKeysConstraints as Truck;
use Illuminate\Validation\Rule;
use App\Constraints\ValidatorConstraints as Schema;

class MaintenanceRequest extends Request {
    protected const SERVICE_MIN = 5;
    protected const SERVICE_MAX = 512;

    public function getRules($partial, $keepRequired, ...$keys): array {
        $ref = [
            Keys::SERVICE => [
                Schema::REQUIRED,
                Schema::cMin(self::SERVICE_MIN),
                Schema::cMax(self::SERVICE_MAX),
            ],
            Keys::KM => [
                Schema::REQUIRED,
                Schema::INTEGER
            ],
            Keys::COST => [
                Schema::REQUIRED,
                Schema::cDecimal(),
            ],
            Keys::DATE => [
                Schema::REQUIRED,
                Schema::cDate(),
            ],
            Keys::TRUCK => [
                Schema::REQUIRED,
                Rule::exists(Truck::TABLE, 'id')
            ]
        ];

        $rules = self::retrieveRules($partial, $ref, $keys, $keepRequired);
        if (count($rules) === 0) {
            throw new Exception('getRules: No key found');
        }
        return $rules;
    }

    // public function messages(): array {
    //     return [
    //         Schema::dRequired(Keys::NAME) => self::getMsgRequired(Trans::NAME),
    //         Schema::dMax(Keys::NAME) => self::getMsgSizeMax(Trans::NAME, self::NAME_MAX),
    //         Schema::dMin(Keys::NAME) => self::getMsgSizeMin(Trans::NAME, self::NAME_MIN),
    //         Schema::dUnique(Keys::NAME) => self::getMsgUnique(Trans::NAME),
    //     ];
    // }
}
