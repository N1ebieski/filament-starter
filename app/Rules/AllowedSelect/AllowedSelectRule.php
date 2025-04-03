<?php

namespace App\Rules\AllowedSelect;

use App\Models\Shared\Attributes\AttributesInterface;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedSelectRule implements ValidationRule
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
        $selects = $this->model->selectable;

        if (! in_array($value, $selects)) {
            $fail('validation.allowed_select.in')->translate([
                'attributes' => implode(', ', $selects),
            ]);
        }
    }
}
