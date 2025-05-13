<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;

class TranslateModExtract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:extract {modJar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract en_us.json from a Minecraft MOD jar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modJar = $this->argument('modJar');
        $jarPath = base_path("mods/{$modJar}");

        if (!file_exists($jarPath)) {
            $this->error("MOD jar not found: {$jarPath}");
            return Command::FAILURE;
        }

        $zip = new ZipArchive();
        if ($zip->open($jarPath) !== true) {
            $this->error("Failed to open MOD jar: {$jarPath}");
            return Command::FAILURE;
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->getNameIndex($i);

            if (preg_match('#^assets/([^/]+)/lang/en_us\.json$#', $entry, $matches)) {
                $modName = $matches[1];
                $content = $zip->getFromName($entry);

                $outputDir = base_path("tmp/{$modName}/lang");
                if (!is_dir($outputDir)) {
                    mkdir($outputDir, 0777, true);
                }

                file_put_contents("{$outputDir}/en_us.json", $content);
                $this->info("Extracted: {$modJar} → tmp/{$modName}/lang/en_us.json");

                $zip->close();
                return Command::SUCCESS;
            }
        }

        $zip->close();
        $this->error("en_us.json not found in: {$modJar}");
        return Command::FAILURE;
    }
}
