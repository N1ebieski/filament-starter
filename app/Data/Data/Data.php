<?php

declare(strict_types=1);

namespace App\Data\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\DataPipeline;
use Spatie\LaravelData\Data as BaseData;
use Illuminate\Contracts\Support\Arrayable;
use App\Data\Pipelines\ModelDataPipe\ModelDataPipe;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsDataPipe;

/**
 * @method self only(string ...$only)
 */
abstract class Data extends BaseData implements Arrayable
{
    use WithDeprecatedCollectionMethod;

    public function toCollect(): Collection
    {
        return Collection::make($this->toArray());
    }

    public static function pipeline(): DataPipeline
    {
        return parent::pipeline()
            ->firstThrough(ModelDataPipe::class)
            ->firstThrough(ObjectDefaultsDataPipe::class);
    }
}
