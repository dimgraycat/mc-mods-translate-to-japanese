<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ZipArchive;

class TranslatePackAll extends Command
{
    use Traits\PackFormats;

    protected $signature = 'translate:pack-all {--ver=}';
    protected $description = 'Create a zip containing all ja_jp.json files for a given version';

    public function handle()
    {
        $ver = $this->option('ver');

        if (!$ver) {
            $this->error('--ver is required');
            return Command::FAILURE;
        }

        $sourceBase = storage_path("tmp/{$ver}");
        if (!is_dir($sourceBase)) {
            $this->error("Directory not found: {$sourceBase}");
            return Command::FAILURE;
        }

        $packFormat = $this->getPackFormat($ver);
        if (!$packFormat) {
            $this->error("Unknown pack_format for version {$ver}");
            return Command::FAILURE;
        }

        $description = env('PACK_DESCRIPTION', "All in Japanese Translation Pack for MC v{$ver}");
        $zipName = "translated-all-in-{$ver}.zip";
        $zipPath = base_path("build/resourcepacks/{$zipName}");

        File::ensureDirectoryExists(dirname($zipPath));

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error("Failed to create zip: {$zipPath}");
            return Command::FAILURE;
        }

        // pack.mcmeta を追加
        $mcmeta = json_encode([
            "pack" => [
                "pack_format" => $packFormat,
                "description" => $description
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $zip->addFromString('pack.mcmeta', $mcmeta);

        // ja_jp.json を追加
        $added = 0;
        $files = File::allFiles($sourceBase);
        foreach ($files as $file) {
            if ($file->getFilename() === 'ja_jp.json') {
                $localPath = 'assets/'.ltrim(str_replace($sourceBase, '', $file->getPathname()), DIRECTORY_SEPARATOR);
                $zip->addFile($file->getPathname(), $localPath);
                $this->line("✔ Added: {$localPath}");
                $added++;
            }
        }

        $zip->close();

        if ($added === 0) {
            $this->warn("No ja_jp.json files found for version {$ver}");
            return Command::SUCCESS;
        }

        $this->info("✅ Created zip: build/resourcepacks/{$zipName}");
        return Command::SUCCESS;
    }
}