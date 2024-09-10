<?php

declare(strict_types=1);

namespace App\Data\Casts\SearchBy;

use Spatie\LaravelData\Casts\Cast;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatchFactory;
use App\Queries\Shared\SearchBy\SearchByInterface;

class SearchByCast implements Cast
{
    private readonly Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model */
        $model = new $modelName();

        $this->model = $model;
    }

    /**
     * @param SearchByInterface|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            if (mb_strlen($value) > 2) {
                return DatabaseMatchFactory::makeDatabaseMatch(
                    term: $value,
                    isOrderBy: true,
                    model: $this->model
                );
            }

            return null;
        }

        return $value;
    }
}
