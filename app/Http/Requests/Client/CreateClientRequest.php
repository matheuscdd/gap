<?php

namespace App\Http\Requests\Client;


use App\Constraints\ClientKeysConstraints as Keys;


class CreateClientRequest extends ClientRequest {
    public function rules(): array {
        return $this->getRules(false, true,...Keys::ALL);
    }
}
