<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function set($lang, Request $request)
    {
        $languages = ['ar', 'en'];
        if (! in_array($lang, $languages)) {
            $lang = 'en';
        }
        $request->session()->put('lang', $lang);
        return back();

    }
}
