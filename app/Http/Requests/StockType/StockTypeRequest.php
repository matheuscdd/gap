<?php

namespace App\Http\Requests\StockType;

use App\Http\Requests\Request;
use App\Constraints\StockTypeKeysConstraints as Keys;
use App\Constraints\StockTypeTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;

class StockTypeRequest extends Request {
    protected const NAME_MIN = 3;
    protected const NAME_MAX = 50;

    public function rules(): array {
        return [
            Keys::NAME => [
                Schema::REQUIRED,
                Schema::cMin(self::NAME_MIN),
                Schema::cMax(self::NAME_MAX),
                Schema::cUnique(Keys::TABLE),
            ]
        ];
    }

    public function messages(): array {
        return [
            Schema::dRequired(Keys::NAME) => self::getMsgRequired(Trans::NAME),
            Schema::dMax(Keys::NAME) => self::getMsgSizeMax(Trans::NAME, self::NAME_MAX),
            Schema::dMin(Keys::NAME) => self::getMsgSizeMin(Trans::NAME, self::NAME_MIN),
            Schema::dUnique(Keys::NAME) => self::getMsgUnique(Trans::NAME),
        ];
    }
}
