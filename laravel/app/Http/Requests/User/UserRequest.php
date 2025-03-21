<?php

namespace App\Http\Requests\User;

use App\Enums\TypeUser;
use App\Http\Requests\Request;
use App\Utils\Utils;
use Exception;
use Illuminate\Validation\Rules\Enum;
use App\Constraints\UserKeysConstraints as Keys;
use App\Constraints\UserTransConstraints as Trans;
use App\Constraints\ValidatorConstraints as Schema;

class UserRequest extends Request {
    protected const EMAIL_MIN = 8;
    protected const EMAIL_MAX = 255;
    protected const NAME_MIN = 3;
    protected const NAME_MAX = 64;
    protected const PASSWD_MIN = 8;
    protected const PASSWD_MAX = 64;
    protected const PHOTO_MIN = 5;
    
    public function getRules($partial, $keepRequired, ...$keys): array {
        $ref = [
            Keys::EMAIL => [
                Schema::REQUIRED,
                Schema::EMAIL,
                Schema::cMin(self::EMAIL_MIN),
                Schema::cMax(self::EMAIL_MAX),
                Schema::unique(Keys::TABLE, $this->route('id')),
            ],
            Keys::NAME  => [
                Schema::REQUIRED,
                Schema::cMin(self::NAME_MIN),
                Schema::cMax(self::NAME_MAX)
            ],
            Keys::PASSWORD  => [
                Schema::REQUIRED,
                Schema::cMin(self::PASSWD_MIN),
                Schema::cMax(self::PASSWD_MAX)
            ],
            Keys::TYPE  => [
                Schema::REQUIRED, new Enum(TypeUser::class)
            ],
            Keys::PHOTO => [
                Schema::cMin(self::PHOTO_MIN),
                Schema::NULLABLE,
            ],
        ];
        $rules = self::retrieveRules($partial, $ref, $keys, $keepRequired);
        if (count($rules) === 0) {
            throw new Exception('getRules: No key found');
        }
        return $rules;
    }

    public function messages(): array {
        return Utils::arrayFlat([
            self::getMsgEnum(Keys::TYPE, TypeUser::class),
            Schema::dRequired(Keys::TYPE) => self::getMsgRequired(Trans::TYPE),
            Schema::dRequired(Keys::EMAIL) => self::getMsgRequired(Trans::TYPE),
            Schema::dEmail(Keys::EMAIL) => self::getMsgValid(Trans::TYPE),
            Schema::dUnique(Keys::EMAIL) => self::getMsgUnique(Trans::EMAIL),
            Schema::dMin(Keys::EMAIL) => self::getMsgSizeMin(Trans::EMAIL, self::EMAIL_MIN),
            Schema::dMax(Keys::EMAIL) => self::getMsgSizeMax(Trans::EMAIL, self::EMAIL_MAX),
            Schema::dRequired(Keys::NAME) => self::getMsgRequired(Trans::NAME),
            Schema::dMin(Keys::NAME) => self::getMsgSizeMin(Trans::NAME, self::NAME_MIN),
            Schema::dMax(Keys::NAME) => self::getMsgSizeMax(Trans::NAME, self::NAME_MAX),
            Schema::dRequired(Keys::PASSWORD) => self::getMsgRequired(Trans::PASSWORD),
            Schema::dMin(Keys::PASSWORD) => self::getMsgSizeMin(Trans::PASSWORD, self::PASSWD_MIN),
            Schema::dMax(Keys::PASSWORD) => self::getMsgSizeMax(Trans::PASSWORD, self::PASSWD_MAX),
        ]);
    }
}

