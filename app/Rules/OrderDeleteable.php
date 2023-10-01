<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class OrderDeleteable implements Rule
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
        $locate = DB::table('orders')->where('id',$value)->select('order_locate','id_company')->get();
        if (count($locate)>0) {
            if ($locate[0]->order_locate === 0 && $locate[0]->id_company == Auth::user()->company_id) {
                return true;
            }
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'لا يمكن حذف بيانات هذا الطرد.';
    }
}
