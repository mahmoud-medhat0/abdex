<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class files extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function exist($id)
    {
        return "not allowed";
        // return $this->middleware('auth');
    }
    public function tt()
    {
        return $this->middleware('auth');
    }
}
