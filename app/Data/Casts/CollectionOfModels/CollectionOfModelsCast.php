<?php

declare(strict_types=1);

namespace App\Data\Casts\CollectionOfModels;

use App\Data\Casts\Cast as BaseCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final readonly class CollectionOfModelsCast extends BaseCast implements Cast
{
    private Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model */
        $model = new $modelName;

        $this->model = $model;
    }

    /**
     * @param  Collection|array<int>|array<Model>  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (empty($value)) {
            return new Collection;
        }

        if (is_array($value) && is_null(Arr::first($value, fn ($v): bool => $v instanceof Model))) {
            return $this->model->query()
                ->whereIn($this->model->getKeyName(), $value)
                ->get();
        }

        return $value;
    }
}
