<?php
namespace App\Services;

use App\Models\RechargeSplitMode;

class RechargeSplitModeService
{
    public function getUsableInterfaceBySplitMode(RechargeSplitMode $splitMode)
    {
        $default = $splitMode->defaultif()->normal()->first();

    }
}
