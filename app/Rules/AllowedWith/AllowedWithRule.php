<?php

namespace App\Rules\AllowedWith;

use Closure;
use Illuminate\Support\Collection;
use App\Models\HasAttributesInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedWithRule implements ValidationRule
{
    public function __construct(private readonly HasAttributesInterface $model)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param string $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        [$baseRelation, $attributes] = explode(':', $value) + [null, null];

        $withs = $this->model->getWithable();

        if (!in_array($baseRelation, $withs)) {
            $fail('validation.allowed_with.with_in')->translate([
                'relations' => implode(', ', $withs)
            ]);

            return;
        }

        if (!empty($attributes)) {
            Collection::make(explode(',', $attributes))
                ->map(fn (string $attribute): string => trim($attribute))
                ->each(function (string $attribute) use ($baseRelation, $fail) {
                    $relations = explode('.', $baseRelation);

                    $model = $this->model;

                    foreach ($relations as $relation) {
                        /** @var HasAttributesInterface */
                        $model = $model->{$relation}()->make();
                    }

                    $selects = $model->getSelectable();

                    if (!in_array($attribute, $selects)) {
                        $fail('validation.allowed_with.select_in')->translate([
                            'attributes' => implode(', ', $selects)
                        ]);
                    }
                });
        }
    }
}
