<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class Capitalized implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[A-Z]{1}[a-z]{1,}(\s{1}[A-Z]{1}[a-z]{1,})+$/', $value)) {
            $fail(Str::ucfirst($attribute) . " is not capitalized");
        }
    }
}
