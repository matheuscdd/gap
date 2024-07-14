<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class DelUserService {
    public function execute(int $id) {
        $user = User::find($id);
        $user->active = false;
        $user->save();
        return $user;
    }
}
