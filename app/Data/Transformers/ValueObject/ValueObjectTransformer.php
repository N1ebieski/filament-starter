<?php

declare(strict_types=1);

namespace App\Data\Transformers\ValueObject;

use App\ValueObjects\ValueObject;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;
use Spatie\LaravelData\Support\Transformation\TransformationContext;

final class ValueObjectTransformer implements Transformer
{
    /** @param ValueObject $value */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return $value->value;
    }
}
