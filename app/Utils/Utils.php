<?php

namespace App\Utils;

class Utils {
    public static function arrayFlat(array $rawestArray): array {
        $res = [];
        $isCommonArray = array_values($rawestArray) === $rawestArray;
        foreach ($rawestArray as $key => $value) {
            $combo = is_array($value) ? $value : [$key => $value];
            if ($isCommonArray) {
                $res = array_merge($res, $combo);
            } else {
                $res += $combo;
            }
        }
        return $res;
    }
}
