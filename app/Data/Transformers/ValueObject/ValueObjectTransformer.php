<?php

declare(strict_types=1);

namespace App\Data\Transformers\ValueObject;

use App\Data\Transformers\Transformer as BaseTransformer;
use App\Support\ValueObject\ValueObject;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

final class ValueObjectTransformer extends BaseTransformer implements Transformer
{
    /** @param ValueObject $value */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): mixed
    {
        return $value->value;
    }
}
