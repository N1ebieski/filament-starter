<?php

namespace App\Rules\ResourceSelect;

use Closure;
use App\Http\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Support\Resource\ResourceHelper;
use Illuminate\Contracts\Validation\ValidationRule;

class ResourceSelectRule implements ValidationRule
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
        /** @var Resource */
        $resource = ResourceHelper::getResourceName($this->model);

        if (!in_array($value, $resource::getAllowedSelect())) {
            $fail('validation.resource_select.in')->translate([
                'attributes' => implode(', ', $resource::getAllowedSelect())
            ]);
        }
    }
}
