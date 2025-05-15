<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;

class TranslateModPack extends Command
{
    use Traits\PackFormats;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:pack {--name=} {--ver=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package ja_jp.json into a Minecraft resource pack';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mod = $this->option('name');
        $ver = $this->option('ver');

        if (!$mod || !$ver) {
            $this->error('--name と --ver は必須です');
            return Command::FAILURE;
        }

        $src = storage_path("tmp/{$mod}/lang/ja_jp.json");
        if (!file_exists($src)) {
            $this->error("ja_jp.json not found: tmp/{$mod}/lang/ja_jp.json");
            return Command::FAILURE;
        }

        $packFormat = $this->getPackFormat($ver);
        if (!$packFormat) {
            $this->error("Unknown Minecraft version: {$ver}");
            return Command::FAILURE;
        }

        $jarFile = config("modnames.{$mod}.jarFile");
        $description = env('PACK_DESCRIPTION', "Translation Pack for {$mod}");
        $zipPath = base_path("build/resourcepacks/{$jarFile}_{$ver}-ja.zip");
        @mkdir(dirname($zipPath), 0777, true);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFromString("pack.mcmeta", json_encode([
                "pack" => [
                    "pack_format" => $packFormat,
                    "description" => $description
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            $zip->addFile($src, "assets/{$mod}/lang/ja_jp.json");

            $zip->close();
            $this->info("Created resource pack: build/resourcepacks/{$jarFile}_{$ver}-ja.zip");
            return Command::SUCCESS;
        } else {
            $this->error("Failed to create zip file.");
            return Command::FAILURE;
        }
    }
}
