<?php

namespace App\Rules;

use App\Models\IB21;
use Illuminate\Contracts\Validation\Rule;

class IB21Period implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $begin = date("Y-m-01", strtotime($value));
        $end = date("Y-m-t", strtotime($value));

        $ib21 = IB21::where('time', '>=', $begin)->where('time', '<=', $end)->get();
        return count($ib21) == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Periode sudah ditambahkan. Silakan edit periode tersebut';
    }
}
