<?php

namespace App\Http\Requests\Truck;

use App\Http\Requests\Request;
use Exception;
use App\Constraints\TruckKeysConstraints as Keys;
use App\Constraints\TruckTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;

class TruckRequest extends Request {
    protected const PLATE_SIZE = 7;
    protected const PHOTO_MIN = 5;

    public function getRules($partial, $keepRequired, ...$keys): array {
        $ref = [
            Keys::PLATE => [
                Schema::REQUIRED,
                Schema::ALPHANUM,
                Schema::cMin(self::PLATE_SIZE),
                Schema::cMax(self::PLATE_SIZE),
                Schema::cUnique(Keys::TABLE),
                Schema::UPPERCASE(),
            ],
            Keys::PHOTO => [
                Schema::cMin(self::PHOTO_MIN),
                Schema::NULLABLE,
            ],
            Keys::AXIS => [
                Schema::REQUIRED,
                Schema::INTEGER,
                Schema::NUMERIC,
    
            ],
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
