<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class validuid implements Rule
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
        $vagent = array();
        $agents = DB::table('uid')->select('*')->get();
        foreach ($agents as $agent){
            array_push($vagent,$agent->uid);
        }
        if (in_array($value,$vagent)){
            if(DB::table('uid')->where('uid',$value)->select('active')->get()[0]->active =='0'){
                return 'برجاء التواصل مع الادمن لاعاده تفعيل حسابك';
            }
            return true;
        }
        else{
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
        return 'uid is not valid';
    }
}
