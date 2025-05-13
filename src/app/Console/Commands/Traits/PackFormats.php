<?php

namespace App\Console\Commands\Traits;

trait PackFormats
{
    public function getPackFormat(string $version): ?int
    {
        foreach (config('packformat') as $range) {
            if (version_compare($version, $range['from'], '>=') && version_compare($version, $range['to'], '<=')) {
                return $range['format'];
            }
        }
        return null;
    }
}