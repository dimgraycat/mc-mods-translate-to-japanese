<?php

use App\Helpers\RomanHelper;
use App\Helpers\JsonHelper;

if (!function_exists('intToRoman')) {
    function intToRoman(int $num): string {
        return RomanHelper::intToRoman($num);
    }
}

if (!function_exists('getJsonFromFile')) {
    /**
     * 指定されたファイルパスからJSONを読み込み、デコードして返します。
     * エラーが発生した場合はログに記録し、空の配列 `[]` を返します。
     *
     * @param string $filePath ファイルへの絶対パスまたは相対パス。
     * @param bool $assoc trueの場合、連想配列として返します。falseの場合、オブジェクトとして返します。
     * @return mixed デコードされたデータ、またはエラー時は空の配列 `[]`
     */
    function getJsonFromFile(string $filePath, bool $assoc = true) {
        return JsonHelper::getJsonFromFile($filePath, $assoc);
    }
}