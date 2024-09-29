<?php

namespace App\Http\Requests\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;

class CreateDeliveryRequest extends DeliveryRequest {
    public function rules(): array {
        return $this->getRules(false, ...Keys::ALL);
    }
}

