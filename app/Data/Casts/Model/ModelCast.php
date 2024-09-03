<?php

declare(strict_types=1);

namespace App\Data\Casts\Model;

use Spatie\LaravelData\Casts\Cast;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class ModelCast implements Cast
{
    private readonly Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model */
        $model = new $modelName();

        $this->model = $model;
    }

    /**
     * @param Model|string|int|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (!is_null($value) && !$value instanceof $this->model) {
            return $this->model->newQuery()
                ->whereKey($value)
                ->first();
        }

        return $value;
    }
}
