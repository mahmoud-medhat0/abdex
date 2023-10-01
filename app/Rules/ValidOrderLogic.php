<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class ValidOrderLogic implements Rule
{
    private $value;
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
        $this->value = $value;
        $sup = DB::table('orders')->where('id','=',$value)->select('delegate_supply','company_supply')->get()[0];
        if ($sup->delegate_supply==0 || $sup->company_supply==0) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'لا يمكن تحديث هذا الطرد  '.$this->value;
    }
}
