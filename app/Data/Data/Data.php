<?php

declare(strict_types=1);

namespace App\Data\Data;

use App\Data\Pipelines\ModelDataPipe\ModelDataPipe;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsDataPipe;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Concerns\WithDeprecatedCollectionMethod;
use Spatie\LaravelData\Data as BaseData;
use Spatie\LaravelData\DataPipeline;

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
