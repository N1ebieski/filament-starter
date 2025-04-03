<?php

namespace App\Rules\AllowedSort;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Support\Query\Sorts\SortsHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class AllowedSortRule implements ValidationRule
{
    public function __construct(private AttributesInterface $model) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param  string  $value
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $sorts = SortsHelper::getAttributesWithOrder($this->model->sortable);

        if (! in_array($value, $sorts)) {
            $fail('validation.allowed_sort.in')->translate([
                'attributes' => implode(', ', $sorts),
            ]);
        }
    }
}
