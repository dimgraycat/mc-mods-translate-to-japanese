<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class JsonHelper
{
    /**
     * 指定されたファイルパスからJSONファイルを読み込み、その内容を文字列として返します。
     * ファイルが存在しない場合や読み込みに失敗した場合は、ログに記録し、
     * 空のJSONオブジェクト文字列 ("{}") を返します。
     *
     * @param string $filePath ファイルへの絶対パスまたは相対パス。
     * @return string JSON文字列。エラー時は空のJSONオブジェクト文字列 `"{}"`
     */
    public static function getJsonFromFile(string $filePath): string
    {
        if (!File::exists($filePath)) {
            Log::warning("JsonHelper: File not found at path '{$filePath}'. Returning empty JSON object string.");
            return '{}';
        }

        try {
            $data = File::get($filePath);
            return json_encode(json_decode($data, true), true);
        } catch (\Exception $e) {
            Log::error("JsonHelper: Failed to read file content from path '{$filePath}'. Error: " . $e->getMessage() . ". Returning empty JSON object string.");
            return '{}';
        }
    }
}