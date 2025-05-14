<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslateDiff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:diff {--name=} {--ver=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '出力された en_us.json と ja_jp.json を比較し、翻訳されていない項目を diff 出力する';

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

        $enPath = base_path("tmp/{$ver}/{$mod}/lang/en_us.json");
        $jaPath = base_path("translated/{$ver}/{$mod}/lang/ja_jp.json");
        $diffPath = base_path("tmp/{$ver}/{$mod}/lang/en_us.diff.json");

        if (!File::exists($enPath)) {
            $this->error("英語ファイルが見つかりません: {$enPath}");
            return Command::FAILURE;
        }

        if (!File::exists($jaPath)) {
            $this->warn("翻訳ファイルが見つかりません（空として扱います）: {$jaPath}");
        }

        $en = json_decode(File::get($enPath), true);
        $ja = File::exists($jaPath) ? json_decode(File::get($jaPath), true) : [];

        if (!is_array($en) || !is_array($ja)) {
            $this->error("JSONの読み込みに失敗しました");
            return Command::FAILURE;
        }

        $diff = [];
        foreach ($en as $key => $value) {
            if (!array_key_exists($key, $ja)) {
                $diff[$key] = $value;
            }
        }

        File::ensureDirectoryExists(dirname($diffPath));
        File::put($diffPath, json_encode($diff, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("✅ 差分出力完了: tmp/{$ver}/{$mod}/lang/en_us.diff.json");
        $this->info("🔍 未翻訳項目数: " . count($diff));
        return Command::SUCCESS;
    }
}
