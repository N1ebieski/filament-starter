<?php

namespace App\Data\Transformers\Collection;

use App\Data\Data\Data;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Partials\PartialsCollection;
use Spatie\LaravelData\Support\Partials\Segments\FieldsPartialSegment;
use Spatie\LaravelData\Support\Partials\Segments\NestedPartialSegment;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\ArrayableTransformer;
use Spatie\LaravelData\Transformers\Transformer;

class CollectionTransformer implements Transformer
{
    public function __construct(private readonly ArrayableTransformer $arrayableTransformer) {}

    private function getFieldsFromPartials(string $name, ?PartialsCollection $partials): array
    {
        $fields = [];

        if (is_null($partials)) {
            return $fields;
        }

        foreach ($partials as $partial) {
            $segments = $partial->segments;

            $last = end($segments);
            $previous = prev($segments);

            if (
                ! isset($previous)
                || ! ($previous instanceof NestedPartialSegment)
                || ! ($last instanceof FieldsPartialSegment)
                || $previous->field !== $name
            ) {
                continue;
            }

            array_push($fields, ...$last->fields);
        }

        return $fields;
    }

    /**
     * @param  Collection  $value
     */
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {
        if (! ($value[0] instanceof Data)) {
            return $this->arrayableTransformer->transform($property, $value, $context);
        }

        $only = $this->getFieldsFromPartials($property->name, $context->onlyPartials);
        $except = $this->getFieldsFromPartials($property->name, $context->exceptPartials);

        return $value->map(function (Data $data) use ($only, $except): array {
            return $data->toCollect()
                ->when(! empty($only), fn (Collection $collection): Collection => $collection->only($only))
                ->when(! empty($except), fn (Collection $collection): Collection => $collection->except($except))
                ->toArray();
        })->toArray();
    }
}
