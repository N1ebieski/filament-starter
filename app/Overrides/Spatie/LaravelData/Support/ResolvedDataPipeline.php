<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\LaravelData\Support;

use Override;
use Spatie\LaravelData\Normalizers\Normalized\Normalized;
use Spatie\LaravelData\Normalizers\Normalized\UnknownProperty;
use Spatie\LaravelData\Support\ResolvedDataPipeline as BaseResolvedDataPipeline;

/**
 * Default ResolvedDataPipeline from Spatie Laravel Data package doesn't include lazy attributes,
 * so it tries get all model properties even if they dosn't exist
 */
final class ResolvedDataPipeline extends BaseResolvedDataPipeline
{
    #[Override]
    protected function transformNormalizedToArray(Normalized $normalized): array
    {
        $properties = [];

        foreach ($this->dataClass->properties as $property) {
            $name = $property->inputMappedName ?? $property->name;

            if (! is_null($property->type->lazyType)) {
                continue;
            }

            $value = $normalized->getProperty($name, $property);

            if ($value === UnknownProperty::create()) {
                continue;
            }

            $properties[$name] = $value;
        }

        return $properties;
    }
}
