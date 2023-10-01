<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    // public function __construct()
    // {
    //     switch (Auth::user()->rank_id) {
    //         case '1':
    //             # code...
    //             break;
    //         case '3':
    //             session()->flash('parent','operation');
    //             dd(session()->get('parent'));
    //             break;
    //         default:
    //             # code...
    //             break;
    //     }
    
    // }
}
