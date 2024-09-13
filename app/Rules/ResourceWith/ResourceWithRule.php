<?php

namespace App\Rules\ResourceWith;

use Closure;
use App\Http\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Support\Resource\ResourceHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ResourceWithRule implements ValidationRule
{
    private readonly Model $model;

    public function __construct(string $modelName)
    {
        /** @var Model */
        $model = new $modelName();

        $this->model = $model;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param string $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        [$baseRelation, $attributes] = explode(':', $value);

        /** @var Resource */
        $resource = ResourceHelper::getResourceName($this->model);

        if (!in_array($baseRelation, $resource::getAllowedWith())) {
            $fail('validation.resource_with.with_in')->translate([
                'relations' => implode(', ', $resource::getAllowedWith())
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
                        $model = $model->{$relation}()->make();
                    }

                    /** @var Resource */
                    $resource = ResourceHelper::getResourceName($model);

                    if (!in_array($attribute, $resource::getAllowedSelect())) {
                        $fail('validation.resource_with.select_in')->translate([
                            'attributes' => implode(', ', $resource::getAllowedSelect())
                        ]);
                    }
                });
        }
    }
}
