<?php

namespace App\Rules\AllowedSelect;

use Closure;
use App\Models\HasAttributesInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedSelectRule implements ValidationRule
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
        $selects = $this->model->getSelectable();

        if (!in_array($value, $selects)) {
            $fail('validation.allowed_select.in')->translate([
                'attributes' => implode(', ', $selects)
            ]);
        }
    }
}
