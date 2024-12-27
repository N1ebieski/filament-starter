<?php

namespace App\Rules\AllowedWith;

use App\Models\Shared\Attributes\AttributesInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;

class AllowedWithRule implements ValidationRule
{
    public function __construct(private readonly AttributesInterface $model) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param  string  $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var string $baseRelation */
        [$baseRelation, $attributes] = explode(':', $value) + [null, null];

        $withs = $this->model->withable;

        if (! in_array($baseRelation, $withs)) {
            $fail('validation.allowed_with.with_in')->translate([
                'relations' => implode(', ', $withs),
            ]);

            return;
        }

        if (! empty($attributes)) {
            Collection::make(explode(',', $attributes))
                ->map(fn (string $attribute): string => trim($attribute))
                ->each(function (string $attribute) use ($baseRelation, $fail) {
                    $relations = explode('.', $baseRelation);

                    $model = $this->model;

                    foreach ($relations as $relation) {
                        /** @var AttributesInterface */
                        $model = $model->{$relation}()->make();
                    }

                    $selects = $model->selectable;

                    if (! in_array($attribute, $selects)) {
                        $fail('validation.allowed_with.select_in')->translate([
                            'attributes' => implode(', ', $selects),
                        ]);
                    }
                });
        }
    }
}
