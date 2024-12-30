<?php

declare(strict_types=1);

namespace App\Data\Casts\Model;

use App\Data\Casts\Cast as BaseCast;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ModelCast extends BaseCast implements Cast
{
    public function __construct(private readonly ?string $modelName = null) {}

    /**
     * @param  Model|string|int|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        /** @var \Spatie\LaravelData\Support\Types\NamedType */
        $type = $property->type->type;

        $modelName = $this->modelName ?? $type->name;

        if (! is_null($value) && ! $value instanceof $modelName) {
            /** @var Model */
            $model = new $modelName;

            return $model->newQuery()->whereKey($value)->first();
        }

        return $value;
    }
}
