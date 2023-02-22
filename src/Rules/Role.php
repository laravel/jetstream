<?php

namespace Laravel\Jetstream\Rules;

use Illuminate\Contracts\Validation\Rule;
use Laravel\Jetstream\Jetstream;

class Role implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return array_key_exists($value, Jetstream::$roles);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('The :attribute must be a valid role.');
    }
}
