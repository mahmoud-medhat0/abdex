<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class orderdisableeditcompany
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
        $locate = DB::table('orders')->where('id',$request->id)->select('order_locate','id_company')->get();
        if (count($locate)>0) {
            if ($locate[0]->order_locate === 0 && $locate[0]->id_company == Auth::user()->company_id) {
                return $next($request);
            }
            session()->flash('error','لا يمكن تعديل هذا الطرد.');
            return redirect()->route('home');
        }
        session()->flash('error','طرد غير موجود');
        return redirect()->route('home');
    }
}
