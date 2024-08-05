<?php

declare(strict_types=1);

namespace App\Data\Casts\CollectionOfModels;

use Spatie\LaravelData\Casts\Cast;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class CollectionOfModelsCast implements Cast
{
    private readonly Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model */
        $model = new $modelName();

        $this->model = $model;
    }

    /**
     * @param array<int> $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_array($value) && (array_search(fn ($v) => $v instanceof Model, $value) === false)) {
            return $this->model->query()
                ->whereIn($this->model->getKeyName(), $value)
                ->get();
        }

        return $value;
    }
}
