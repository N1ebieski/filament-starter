<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Drivers\DatabaseMatch\Splits;

use App\Data\Data\Data;
use Illuminate\Database\Eloquent\Model;

final class Splits extends Data
{
    public function __construct(
        public string $term,
        public readonly ?Model $model = null,
        public ?array $attributes = null,
        public ?array $relations = null,
        public ?array $exacts = null,
        public ?array $looses = null,
    ) {
    }
}
