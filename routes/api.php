<?php

use App\Rules\validuid;
use Illuminate\Http\Request;
use App\Http\Controllers\api\orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Apilogin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(Apilogin::class)->group(function(){
    Route::post('/login','login');
});
Route::post('/check-token', function (Request $request) {
    if ($request->bearerToken() || !Auth::guard('sanctum')->check()) {
        return response()->json([
            'message' => '1',
        ]);
    }
    return response()->json([
            'message'=>'0'
        ]);
});
Route::controller(orders::class)->group(function(){
    Route::post('orders/search','search');
    Route::post('orders/all','all');
    Route::post('orders/store','store_m');
    Route::post('orders/returned','returned');
    Route::post('orders/delayed','delayed');
    Route::post('orders/money','money');
    Route::post('orders/delete','delete');
    Route::post('orders/update','update');
});
