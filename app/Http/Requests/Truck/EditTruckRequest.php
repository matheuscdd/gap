<?php

namespace App\Http\Requests\Truck;

use App\Constraints\TruckKeysConstraints as Keys;

class EditTruckRequest extends TruckRequest {
    public function rules(): array {
        return $this->getRules(true, false,...Keys::ALL);
    }
}

