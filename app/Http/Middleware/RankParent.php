<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;

class RankParent
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
        switch (auth()->user()->rank_id) {
            case '1':
                session()->put('parent', 'parent1');
                break;
            case '2':
                session()->put('parent', 'customer_service');
                break;
            case '3':
                session()->put('parent', 'operation');
                break;
            case '5':
                session()->put('parent', 'accountant');
                break;
            case '7':
                session()->put('parent', 'company');
                
            case '8':
                session()->put('parent', 'agent');
                break;
            default:
                Auth()->logout();
                break;
        }
        return $next($request);
    }
}
