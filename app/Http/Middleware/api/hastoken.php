<?php

namespace App\Http\Middleware\api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class hastoken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $vagent = array();
        $agents = DB::table('personal_access_tokens')->select('id','token')->get();
        foreach ($agents as $agent) {
            array_push($vagent, $agent->id."|".$agent->token);
        }
        if (in_array($request->token, $vagent)) {
            return $next($request);
        }
        return response()->json(['error' => 'some thing went wrong'], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
