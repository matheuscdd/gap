<?php

namespace App\Http\Requests\Driver;

use App\Http\Requests\Request;
use Exception;
use App\Constraints\DriverKeysConstraints as Keys;
use App\Constraints\DriverTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;

class DriverRequest extends Request {
    protected const CPF_SIZE = 11;
    protected const NAME_MIN = 3;
    protected const NAME_MAX = 64;

    public function getRules($partial, $keepRequired, ...$keys): array {
        $ref = [
            Keys::NAME => [
                Schema::REQUIRED,
                Schema::cMin(self::NAME_MIN),
                Schema::cMax(self::NAME_MAX),
            ],
            Keys::CPF => [
                Schema::REQUIRED,
                Schema::cRegex(Schema::ONLY_NUMBERS),
                Schema::cMin(self::CPF_SIZE),
                Schema::cMax(self::CPF_SIZE),
                Schema::unique(Keys::TABLE, $this->route('id')),
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
