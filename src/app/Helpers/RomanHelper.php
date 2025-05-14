<?php

namespace App\Helpers;

class RomanHelper
{
    public static function intToRoman(int $num): string
    {
        $map = [
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        ];

        $roman = '';
        foreach ($map as $symbol => $value) {
            while ($num >= $value) {
                $roman .= $symbol;
                $num -= $value;
            }
        }
        return $roman;
    }
}