<?php

namespace App\Http\Requests\Maintenance;


class CreateMaintenanceRequest extends MaintenanceRequest {
    public function rules(): array {
        return $this->getRules(false, true);
    }
}

