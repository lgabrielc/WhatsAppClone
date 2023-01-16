<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InvalidEmail implements Rule
{
    public $email;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($email = null)
    {
        $this->email = $email;
        //
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
        return auth()->user()->contacts()
            ->whereHas('user', function ($query) use ($value) {
                $query->where('email', $value)
                    ->when($this->email, function ($query) {
                        $query->where('email', '!=', $this->email);
                    });
            })->count() === 0;
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El correo electrónico ingresado ya se encuentra registrado';
    }
}
