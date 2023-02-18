<?php

namespace Laravel\Jetstream\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Laravel\Jetstream\Jetstream;

class Role implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! in_array($value, array_keys(Jetstream::$roles))) {
            $fail('The :attribute must be a valid role.')->translate();
        }
    }
}
