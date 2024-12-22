<?php

namespace App\Http\Requests\Delivery;

use App\Constraints\DeliveryKeysConstraints as Keys;

class CreateDeliveryPartialRequest extends DeliveryRequest {
    public function rules(): array {
        return $this->getRules(true, true,
            Keys::DELIVERY_DATE, 
            Keys::DELIVERY_ADDRESS, 
            Keys::UNLOADED, 
            Keys::UNLOADING_COST, 
            Keys::DRIVER,
            Keys::STOCKS
        );
    }
}

