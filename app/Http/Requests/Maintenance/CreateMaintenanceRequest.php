<?php

namespace App\Http\Requests\Maintenance;

use App\Constraints\MaintenanceKeysConstraints as Keys;

class CreateMaintenanceRequest extends MaintenanceRequest {
    public function rules(): array {
        return $this->getRules(false, true, ...Keys::ALL);
    }
}

