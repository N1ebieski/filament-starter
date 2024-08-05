<?php

declare(strict_types=1);

namespace App\Data\Pipelines\ModelDataPipe;

use Illuminate\Database\Eloquent\Model;

interface PrepareFromModelInterface
{
    public static function prepareFromModel(Model $model, array $properties): array;
}
