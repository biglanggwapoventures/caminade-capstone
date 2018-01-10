<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class CustomerRole implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return User::whereRole('CUSTOMER')
            ->whereId($value)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute does not exist';
    }
}
