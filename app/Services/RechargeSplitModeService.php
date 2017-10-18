<?php
namespace App\Services;

use App\Models\RechargeSplitMode;

class RechargeSplitModeService
{
    public function getUsableInterfaceBySplitMode(RechargeSplitMode $splitMode)
    {
        $if = $splitMode->defaultif()->normal()->first();
        if (!$if) {
            $if = $splitMode->spareif()->normal()->first();
        }
        return $if;
    }
}
