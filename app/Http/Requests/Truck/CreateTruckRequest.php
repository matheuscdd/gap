<?php

namespace App\Http\Requests\Truck;


class CreateTruckRequest extends TruckRequest {
    public function rules(): array {
        return $this->getRules(false, true);
    }
}

