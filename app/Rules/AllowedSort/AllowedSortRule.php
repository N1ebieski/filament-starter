<?php

namespace App\Rules\AllowedSort;

use App\Models\HasAttributesInterface;
use App\Support\Query\Sorts\SortsHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedSortRule implements ValidationRule
{
    public function __construct(private readonly HasAttributesInterface $model) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param  string  $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sorts = SortsHelper::getAttributesWithOrder($this->model->getSortable());

        if (! in_array($value, $sorts)) {
            $fail('validation.allowed_sort.in')->translate([
                'attributes' => implode(', ', $sorts),
            ]);
        }
    }
}
