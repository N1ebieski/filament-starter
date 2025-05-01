<?php

declare(strict_types=1);

namespace App\Data\Casts\SearchBy;

use App\Data\Casts\Cast as BaseCast;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Queries\Shared\SearchBy\SearchByInterface;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class SearchByCast extends BaseCast implements Cast
{
    //@phpstan-ignore-next-line
    private readonly Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model $model */
        $model = new $modelName;

        $this->model = $model;
    }

    /**
     * @param  SearchByInterface|string|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value) && mb_strlen($value) > 0) {
            return new Scout(query: $value);
        }

        return null;
    }
}
