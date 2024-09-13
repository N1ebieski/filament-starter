<?php

namespace App\Rules\ResourceSelect;

use Closure;
use App\Models\HasAttributesInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class ResourceSelectRule implements ValidationRule
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
        if (!in_array($value, $this->model->getSelectable())) {
            $fail('validation.resource_select.in')->translate([
                'attributes' => implode(', ', $this->model->getSelectable())
            ]);
        }
    }
}
