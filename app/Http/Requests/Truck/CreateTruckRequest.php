<?php

namespace App\Http\Requests\Truck;

use App\Constraints\TruckKeysConstraints as Keys;

class CreateTruckRequest extends TruckRequest {
    public function rules(): array {
        return $this->getRules(false, true, ...Keys::ALL);
    }
}

