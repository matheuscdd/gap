<?php

namespace App\Http\Requests\Client;


use App\Constraints\ClientKeysConstraints as Keys;


class EditClientRequest extends ClientRequest {
    public function rules(): array {
        return $this->getRules(true, ...Keys::ALL);
    }
}
