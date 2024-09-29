<?php

namespace App\Http\Requests\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;

class EditDeliveryRequest extends DeliveryRequest {
    public function rules(): array {
        return $this->getRules(true, ...Keys::ALL);
    }
}

