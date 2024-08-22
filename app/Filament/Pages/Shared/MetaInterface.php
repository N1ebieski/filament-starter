<?php

declare(strict_types=1);

namespace App\Filament\Pages\Shared;

use App\View\Metas\MetaInterface as ViewMetaInterface;

interface MetaInterface
{
    public function getMeta(): ViewMetaInterface;
}
