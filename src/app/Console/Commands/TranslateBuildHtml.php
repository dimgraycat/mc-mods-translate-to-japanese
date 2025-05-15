<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslateBuildHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:build-html';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $html = view('index', [
            'year' => Carbon::today()->year,
            'pageName' => null,
        ])->render();
        $outputPath = base_path('public/index.html');
        File::ensureDirectoryExists(dirname($outputPath));
        File::put($outputPath, $html);
    }
}
