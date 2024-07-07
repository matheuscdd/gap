<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class Utils {
    public static function arrayFlat(array $rawestArray): array {
        $res = [];
        foreach ($rawestArray as $key => $value) {
            $res += is_array($value) ? $value : [$key => $value];
        }
        return $res;
    }
}
