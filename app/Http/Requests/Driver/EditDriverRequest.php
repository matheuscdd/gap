<?php

namespace App\Http\Requests\Driver;

use App\Constraints\DriverKeysConstraints as Keys;

class EditDriverRequest extends DriverRequest {
    public function rules(): array {
        return $this->getRules(true, false,...Keys::ALL);
    }
}

