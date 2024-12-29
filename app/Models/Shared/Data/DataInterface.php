<?php

declare(strict_types=1);

namespace App\Models\Shared\Data;

use App\Data\Data\Data;

interface DataInterface
{
    public Data $data { get; }
}
