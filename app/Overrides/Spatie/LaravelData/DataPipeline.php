<?php

declare(strict_types=1);

namespace App\Overrides\Spatie\LaravelData;

use App\Overrides\Spatie\LaravelData\Support\ResolvedDataPipeline;
use Normalizer;
use Override;
use Spatie\LaravelData\DataPipeline as BaseDataPipeline;
use Spatie\LaravelData\DataPipes\DataPipe;

/**
 * @see \App\Overrides\Spatie\LaravelData\Support\ResolvedDataPipeline
 */
final class DataPipeline extends BaseDataPipeline
{
    #[Override]
    public function resolve(): ResolvedDataPipeline
    {
        $normalizers = array_merge(
            $this->normalizers,
            $this->classString::normalizers()
        );

        /** @var \Spatie\LaravelData\Normalizers\Normalizer[] $normalizers */
        $normalizers = array_map(
            fn (string|Normalizer $normalizer) => is_string($normalizer) ? app($normalizer) : $normalizer,
            $normalizers
        );

        /** @var \Spatie\LaravelData\DataPipes\DataPipe[] $pipes */
        $pipes = array_map(
            fn (string|DataPipe $pipe) => is_string($pipe) ? app($pipe) : $pipe,
            $this->pipes
        );

        return new ResolvedDataPipeline(
            $normalizers,
            $pipes,
            $this->dataConfig->getDataClass($this->classString)
        );
    }
}
