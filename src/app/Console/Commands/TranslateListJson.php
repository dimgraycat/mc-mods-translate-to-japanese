<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslateListJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:list-json {--base-url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'translatedフォルダから index.json を出力（GitHub Pages 用）';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = $this->option('base-url') ?? config('resources.base_url');
        $translatedBase = base_path('translated');
        $outputPath = base_path('public/index.json');

        $mods = [];

        foreach (File::directories($translatedBase) as $verDir) {
            $ver = basename($verDir);
            foreach (File::directories($verDir) as $modDir) {
                $mod = basename($modDir);

                $jarFile = config("modnames.{$mod}.jarFile", $mod);
                $zipFileName = "{$jarFile}_{$ver}-ja.zip";
                $zipFilePath = base_path("build/resourcepacks/{$zipFileName}");
                $downloadUrl = "{$baseUrl}/{$zipFileName}";

                $mods[$mod]['id'] = config("modnames.{$mod}.modName", $mod);
                $mods[$mod]['name'] = config("modnames.{$mod}.displayName", $mod);
                $mods[$mod]['versions'][] = $ver;

                $linkData = [
                    'file' => $zipFileName,
                    'url' => $downloadUrl,
                ];

                if (File::exists($zipFilePath)) {
                    $linkData['sha1'] = sha1_file($zipFilePath);
                    $linkData['size'] = $this->formatFileSize(filesize($zipFilePath));
                } else {
                    $this->warn("⚠️ File not found, sha1 and size for {$zipFileName} will be null. Path: {$zipFilePath}. 'file' key will still be present.");
                    $linkData['sha1'] = null;
                    $linkData['size'] = null;
                }
                $mods[$mod]['links'][$ver] = $linkData;
            }
        }

        $output = array_values(array_map(function ($entry) {
            $entry['versions'] = array_values(array_unique($entry['versions']));
            return $entry;
        }, $mods));

        File::ensureDirectoryExists(dirname($outputPath));
        File::put($outputPath, json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info("✅ index.json を生成しました: public/index.json");
        return Command::SUCCESS;
    }

    /**
     * ファイルサイズを人間が読みやすい形式にフォーマットする
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    private function formatFileSize(int $bytes, int $precision = 2): string
    {
        if ($bytes === 0) {
            return "0 KB";
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $targetMinUnitIndex = 1; // KBのインデックス

        // 1024を底とする対数を計算して、適切な単位のインデックスを決定
        $pow = floor(log($bytes) / log(1024));

        if ($pow < $targetMinUnitIndex) {
            // 計算された単位がBの場合、KBに変換
            $value = $bytes / 1024;
            return round($value, $precision) . ' ' . $units[$targetMinUnitIndex];
        }

        $value = $bytes / (1 << (10 * $pow));
        return round($value, $precision) . ' ' . $units[min($pow, count($units) - 1)];
    }
}