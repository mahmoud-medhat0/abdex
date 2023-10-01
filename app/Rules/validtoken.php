<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class validtoken implements Rule
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
        $vagent = array();
        $agents = DB::table('personal_access_tokens')->select('*')->get();

        foreach ($agents as $agent){
            array_push($vagent,$agent->token);
        }
        if (in_array($value,$vagent)){
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
        return response()->json(['error'=>'some thing went wrong'],200,[],JSON_UNESCAPED_UNICODE);
    }
}
