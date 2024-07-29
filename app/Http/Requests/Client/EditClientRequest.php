<?php

namespace App\Http\Requests\Client;


use App\Constraints\ClientKeysConstraints as Keys;


class EditClientRequest extends ClientRequest {
    public function rules(): array {
        $userRequest = new ClientRequest();
        return $userRequest->getRules(true, ...Keys::ALL);
    }
}
