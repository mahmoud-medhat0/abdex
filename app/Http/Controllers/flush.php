<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class flush extends Controller
{
    public function flash(Request $request)
    {
        $request->session()->put('active','helloold');
        $request->session()->put('active1','v2');
        $v = $request->session()->flash('active1','54694849894984');
        dd($request->session()->all());
        // return $request->session()->get('active');
    }
}
