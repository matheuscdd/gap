<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\Request;
use Exception;
use App\Constraints\ClientKeysConstraints as Keys;
use App\Constraints\ClientTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;

class ClientRequest extends Request {
    protected const CNPJ_MIN = 14;
    protected const CNPJ_MAX = 14;
    protected const NAME_MIN = 8;
    protected const NAME_MAX = 128;
    protected const CEP_SIZE = 8;
    protected const ADDRESS_MIN = 20;
    protected const ADDRESS_MAX = 256;
    protected const CELLPHONE_SIZE = 11;


    public function getRules($partial, $keepRequired,...$keys): array {
        $ref = [
            Keys::NAME => [
                Schema::REQUIRED,
                Schema::cMin(self::NAME_MIN),
                Schema::cMax(self::NAME_MAX),
            ],
            Keys::CNPJ => [
                Schema::cRegex(Schema::ONLY_NUMBERS),
                Schema::cMin(self::CNPJ_MIN),
                Schema::cMax(self::CNPJ_MAX),
                Schema::unique(Keys::TABLE, $this->route('id')),
            ],
            Keys::CEP => [
                Schema::cRegex(Schema::ONLY_NUMBERS),
                Schema::cDigits(self::CEP_SIZE),
            ],
            Keys::ADDRESS => [
                Schema::REQUIRED,
                Schema::cMin(self::ADDRESS_MIN),
                Schema::cMax(self::ADDRESS_MAX),
            ],
            Keys::CELLPHONE => [
                Schema::REQUIRED,
                Schema::cDigits(self::CELLPHONE_SIZE),
                Schema::unique(Keys::TABLE, $this->route('id')),
            ]
        ];
        $rules= self::retrieveRules($partial, $ref, $keys, $keepRequired);
        if (count($rules) === 0) {
            throw new Exception('getRules: No key found');
        }
        return $rules;
    }

    public function messages(): array {
        return [
            Schema::dRequired(Keys::NAME) => self::getMsgRequired(Trans::NAME),
            Schema::dMax(Keys::NAME) => self::getMsgSizeMax(Trans::NAME, self::NAME_MAX),
            Schema::dMin(Keys::NAME) => self::getMsgSizeMin(Trans::NAME, self::NAME_MIN),
            Schema::dRequired(Keys::ADDRESS) => self::getMsgRequired(Trans::ADDRESS),
            Schema::dMin(Keys::ADDRESS) => self::getMsgSizeMin(Trans::ADDRESS, self::ADDRESS_MIN),
            Schema::dMax(Keys::ADDRESS) => self::getMsgSizeMax(Trans::ADDRESS, self::ADDRESS_MAX),
            Schema::dUnique(Keys::CNPJ) => self::getMsgUnique(Trans::CNPJ),
            Schema::dMin(Keys::CNPJ) => self::getMsgSizeMin(Trans::CNPJ, self::CNPJ_MIN),
            Schema::dMax(Keys::CNPJ) => self::getMsgSizeMax(Trans::CNPJ, self::CNPJ_MAX),
            Schema::dDigits(Keys::CEP) => self::getMsgSize(Trans::CEP, self::CEP_SIZE),
            Schema::dUnique(Keys::CELLPHONE) => self::getMsgUnique(Trans::CELLPHONE),
            Schema::dDigits(Keys::CELLPHONE) => self::getMsgSize(Trans::CELLPHONE, self::CELLPHONE_SIZE),
            Schema::dRequired(Keys::CELLPHONE) => self::getMsgRequired(Trans::CELLPHONE),
        ];
    }
}

