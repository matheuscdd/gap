<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Constraints\UserKeysConstraints as Keys;
use App\Constraints\ValidatorConstraints as Schema;
use App\Constraints\UserTransConstraints as Trans;


class RefreshRequest extends Request {
    public function rules():array {
        return [
            Keys::TOKEN => [Schema::REQUIRED]
        ];
    }

    public function messages(): array {
        return [
            Schema::dRequired(Keys::TOKEN) => $this->getMsgRequired(Trans::TOKEN),
        ];
    }
}
