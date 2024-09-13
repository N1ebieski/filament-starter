<?php

namespace App\Rules\ResourceWith;

use Closure;
use App\Http\Resources\Resource;
use Illuminate\Support\Collection;
use App\Models\HasAttributesInterface;
use App\Support\Resource\ResourceHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ResourceWithRule implements ValidationRule
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

        if (!in_array($baseRelation, $this->model->getWithable())) {
            $fail('validation.resource_with.with_in')->translate([
                'relations' => implode(', ', $this->model->getWithable())
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

                    if (!in_array($attribute, $model->getSelectable())) {
                        $fail('validation.resource_with.select_in')->translate([
                            'attributes' => implode(', ', $model->getSelectable())
                        ]);
                    }
                });
        }
    }
}
