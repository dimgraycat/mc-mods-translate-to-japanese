<?php

namespace App\Console\Commands\Traits;

trait PackFormats
{
    protected function getPackFormat(string $version): ?int
    {
        foreach (config('packformat') as $range) {
            if (version_compare($version, $range['from'], '>=') && version_compare($version, $range['to'], '<=')) {
                return $range['format'];
            }
        }
        return null;
    }

    protected function getPackFormatFromList(string $version): array
    {
        $config =  collect(config('packformat'));
        return $config->filter(function ($item) use ($version) {
            return version_compare($item['from'], $version, '>=');
        })->pluck('from')->all();
    }
}