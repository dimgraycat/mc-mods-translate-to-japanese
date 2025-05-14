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

        // 特別パック一覧を走査して追加
        $specialZips = File::glob(base_path('build/resourcepacks/00-translated-all-in-*.zip'));

        foreach ($specialZips as $zipPath) {
            $filename = basename($zipPath);
            if (preg_match('/00-translated-all-in-(.+)\.zip$/', $filename, $m)) {
                $ver = $m[1];
                $modKey = '00-translated-all-in';
                $mods[$modKey]['name'] = "Translated All-In Pack";
                $mods[$modKey]['versions'][] = $ver;
                $mods[$modKey]['links'][] = "{$baseUrl}/{$filename}";
            }
        }

        // enchant-levels 同様に
        $enchantZips = File::glob(base_path('build/resourcepacks/01-enchant-levels-*.zip'));

        foreach ($enchantZips as $zipPath) {
            $filename = basename($zipPath);
            if (preg_match('/01-enchant-levels-(.+)\.zip$/', $filename, $m)) {
                $ver = $m[1];
                $modKey = '01-enchant-levels';
                $mods[$modKey]['name'] = "Enchantment Level Pack";
                $mods[$modKey]['versions'][] = $ver;
                $mods[$modKey]['links'][] = "{$baseUrl}/{$filename}";
            }
        }

        foreach (File::directories($translatedBase) as $verDir) {
            $ver = basename($verDir);
            foreach (File::directories($verDir) as $modDir) {
                $mod = basename($modDir);

                $mods[$mod]['name'] = config("modnames.{$mod}", $mod);
                $mods[$mod]['versions'][] = $ver;
                $mods[$mod]['links'][] = "{$baseUrl}/{$mod}-translate-to-japanese-{$ver}.zip";
            }
        }

        $output = array_values(array_map(function ($entry) {
            $entry['versions'] = array_values(array_unique($entry['versions']));
            $entry['links'] = array_values(array_unique($entry['links']));
            return $entry;
        }, $mods));

        File::ensureDirectoryExists(dirname($outputPath));
        File::put($outputPath, json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $this->info("✅ index.json を生成しました: public/index.json");
        return Command::SUCCESS;
    }
}