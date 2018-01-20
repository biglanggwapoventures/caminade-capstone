<?php

namespace App\Rules;

use App\Appointment;
use Illuminate\Contracts\Validation\Rule;

class AppointmentStatus implements Rule
{
    protected $status;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($status)
    {
        $this->status = $status;
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
        return Appointment::whereId($value)->ofStatus($this->status)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute given is invalid.';
    }
}
