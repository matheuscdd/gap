<?php

namespace App\Http\Requests\Driver;


class CreateDriverRequest extends DriverRequest {
    public function rules(): array {
        return $this->getRules(false, true);
    }
}

