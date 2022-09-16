<?php

declare (strict_types = 1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StringMustBeOneOfTwo implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value == 'online' || $value == 'offline') ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must online or offline';
    }
}
