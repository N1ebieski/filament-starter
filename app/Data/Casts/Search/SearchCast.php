<?php

declare(strict_types=1);

namespace App\Data\Casts\Search;

use App\Queries\Search\Search;
use Spatie\LaravelData\Casts\Cast;
use App\Queries\Search\SearchFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class SearchCast implements Cast
{
    private readonly Model $model;

    public function __construct(string $modelClassname)
    {
        $this->model = new $modelClassname();
    }

    /**
     * @param Search|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            if (mb_strlen($value) > 2) {
                return SearchFactory::makeSearch($value, $this->model);
            }

            return null;
        }

        return $value;
    }
}
