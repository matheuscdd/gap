<?php

namespace App\Http\Requests\Driver;

use App\Constraints\DriverKeysConstraints as Keys;

class CreateDriverRequest extends DriverRequest {
    public function rules(): array {
        return $this->getRules(false, true, ...Keys::ALL);
    }
}

