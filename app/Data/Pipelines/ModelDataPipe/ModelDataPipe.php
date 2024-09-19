<?php

declare(strict_types=1);

namespace App\Data\Pipelines\ModelDataPipe;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataPipes\DataPipe;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataClass;

final class ModelDataPipe implements DataPipe
{
    public function handle(mixed $payload, DataClass $class, array $properties, CreationContext $creationContext): array
    {
        if ($payload instanceof Model) {
            $interfaces = class_implements($class->name);

            if (in_array(PrepareFromModelInterface::class, $interfaces)) {
                return $class->name::prepareFromModel($payload, $properties);
            }
        }

        return $properties;
    }
}
