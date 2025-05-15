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
    protected $signature = 'translate:extract {--mod=} {--ver=}';

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
        $modJar = $this->option('mod');
        $ver = $this->option('ver');

        if (!$modJar || !$ver) {
            $this->error('--mod と --ver は必須です');
            return Command::FAILURE;
        }

        $jarPath = storage_path("mods/{$modJar}");
        if (!file_exists($jarPath)) {
            $this->error("MOD jar not found: {$jarPath}");
            return Command::FAILURE;
        }

        $jarFileNameWithoutExt = pathinfo($jarPath, PATHINFO_FILENAME);

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

                $outputDir = storage_path("tmp/{$ver}/{$modName}/lang");
                if (!is_dir($outputDir)) {
                    mkdir($outputDir, 0777, true);
                }

                file_put_contents("{$outputDir}/en_us.json", $content);
                $this->info("Extracted: {$modJar} → tmp/{$ver}/{$modName}/lang/en_us.json");

                $zip->close();

                $this->addModNameToConfig($modName, $jarFileNameWithoutExt);

                return Command::SUCCESS;
            }
        }

        $zip->close();
        $this->error("en_us.json not found in: {$modJar}");
        return Command::FAILURE;
    }

    protected function addModNameToConfig(string $modName, string $jarFileNameWithoutExt): void
    {
        $modnamesPath = config_path('modnames.php');
        $modnames = file_exists($modnamesPath) ? include $modnamesPath : [];

        // 旧形式（modName => 表示名の文字列）を新形式に変換
        foreach ($modnames as $key => $value) {
            if (is_string($value)) {
                $modnames[$key] = [
                    'modName' => $key,
                    'jarFile' => '',
                    'displayName' => $value,
                ];
            }
        }

        // 追加または更新
        $modnames[$modName]['modName'] = $modName;
        $modnames[$modName]['jarFile'] = $jarFileNameWithoutExt;

        ksort($modnames);

        $export = var_export($modnames, true);
        $export = str_replace("array (", "[", $export);
        $export = str_replace("),", "],", $export);
        $export = str_replace("\n)", "\n]", $export);
        $export = str_replace("=> \n  [", "=> [", $export);
        $export = "<?php\n\nreturn " . $export . ";\n";
        file_put_contents($modnamesPath, $export);

        $this->info("✅ modnames.php に {$modName} を追加または更新しました。");
    }
}
