<?php

namespace App\Http\Requests\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;

class EditDeliveryFullRequest extends DeliveryRequest {
    public function rules(): array {
        return $this->getRules(false, true,...Keys::ALL);
    }
}

