<?php

namespace App\Data\Normalizers\Model;

use App\Data\Normalizers\Normalizer as BaseNormalizer;
use App\Overrides\Spatie\LaravelData\Normalizers\Normalized\NormalizedModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Normalizers\Normalized\Normalized;
use Spatie\LaravelData\Normalizers\Normalizer;

final readonly class ModelNormalizer extends BaseNormalizer implements Normalizer
{
    public function normalize(mixed $value): ?Normalized
    {
        if (! $value instanceof Model) {
            return null;
        }

        return new NormalizedModel($value);
    }
}
