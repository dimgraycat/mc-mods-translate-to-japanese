<?php

use App\Helpers\RomanHelper;

if (!function_exists('intToRoman')) {
    function intToRoman(int $num): string {
        return RomanHelper::intToRoman($num);
    }
}