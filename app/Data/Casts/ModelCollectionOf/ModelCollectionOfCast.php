<?php

declare(strict_types=1);

namespace App\Data\Casts\ModelCollectionOf;

use Spatie\LaravelData\Casts\Cast;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class ModelCollectionOfCast implements Cast
{
    private readonly Model $model;

    public function __construct(string $modelClassname)
    {
        $this->model = new $modelClassname();
    }

    /**
     * @var array<int> $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_array($value)) {
            return $this->model->query()->findMany($value);
        }

        return $value;
    }
}
