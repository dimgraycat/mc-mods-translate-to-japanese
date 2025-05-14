<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TranslateEnchantLevels extends Command
{
    use Traits\PackFormats;
 
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:enchant-levels {--max=} {--ver=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'エンチャントレベル（1～1000）をローマ数字に変換した resourcepack を生成';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $formVersion = $this->option('ver');
        $max = (int) $this->option('max');
        if (!$max) {
            $this->error('--max, --ver オプションは必須です');
            return Command::FAILURE;
        }

        $list = $this->getPackFormatFromList($formVersion);
        foreach ($list as $ver) {
            // pack_format 判定
            $packFormat = $this->getPackFormat($ver);
            if (!$packFormat) {
                $this->error("pack_format が不明です: {$ver}");
                return Command::FAILURE;
            }

            $description = env('PACK_DESCRIPTION', 'Enchantment level romanization');
            $zipName = "01-enchant-levels-{$ver}.zip";
            $zipPath = base_path("build/resourcepacks/{$zipName}");
            File::ensureDirectoryExists(dirname($zipPath));

            // ja_jp.json の生成
            $entries = [];
            for ($i = 1; $i <= $max; $i++) {
                $entries["enchantment.level.{$i}"] = intToRoman($i);
            }

            $langPath = "assets/minecraft/lang/ja_jp.json";
            $mcmeta = json_encode([
                'pack' => [
                    'pack_format' => $packFormat,
                    'description' => $description
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $langJson = json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            // translatedフォルダにも保存
            $translatedPath = base_path("translated/{$ver}/enchant-levels/lang/ja_jp.json");
            File::ensureDirectoryExists(dirname($translatedPath));
            File::put($translatedPath, $langJson);
            $this->line("📁 保存: translated/{$ver}/enchant-levels/lang/ja_jp.json");

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                $this->error("ZIP作成に失敗しました: {$zipPath}");
                return Command::FAILURE;
            }

            $zip->addFromString('pack.mcmeta', $mcmeta);
            $zip->addFromString($langPath, $langJson);
            $zip->close();

            $this->info("✅ Resourcepack 作成完了: build/resourcepacks/{$zipName}");
        }
        return Command::SUCCESS;
    }
}
