<?php

namespace App\Http\Requests\Maintenance;

use App\Constraints\MaintenanceKeysConstraints as Keys;

class EditMaintenanceRequest extends MaintenanceRequest {
    public function rules(): array {
        return $this->getRules(true, false,...Keys::ALL);
    }
}

