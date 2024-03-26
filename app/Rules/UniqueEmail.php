<?php

namespace App\Rules;

use App\Models\SharedUser;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\Rule;

class UniqueEmail implements Rule
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
        // Check for uniqueness in the default database (assuming it's the "users" table)
        $userExists = User::where('email', $value)->exists();

        // Check for uniqueness in the "shared_users" table in the shared database
        $sharedUserExists = SharedUser::where('email', $value)->exists();

        // If the email exists in either table, it's not unique
        return !$userExists && !$sharedUserExists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has already been taken.';
    }
}
