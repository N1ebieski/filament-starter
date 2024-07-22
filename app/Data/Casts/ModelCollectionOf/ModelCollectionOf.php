<?php

declare(strict_types=1);

namespace App\Data\Casts\ModelCollectionOf;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class ModelCollectionOf implements Castable
{
    public static function dataCastUsing(mixed ...$arguments): Cast
    {
        return new class (...$arguments) implements Cast {
            private array $arguments;

            public function __construct(mixed ...$arguments)
            {
                $this->arguments = $arguments;
            }

            public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
            {
                if (is_array($value)) {
                    /** @var Model */
                    $model = $this->arguments[0];

                    return $model::query()->findMany($value);
                }

                return $value;
            }
        };
    }
}
