<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class Apilogin extends Controller
{
    public function login(Request $request)
    {
        if (!isset($request->uid)) {
            return response()->json([
                'error' => 'missing some thing'
            ]);
        }
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return response()->json(['error' => 'حدث خطأ ما','status' => 200], 200, [], JSON_UNESCAPED_UNICODE);
        }elseif (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $id = DB::table('users')->select('id', 'rank_id')->where('username', '=', $request->username)->get()[0];
            if ($id->rank_id == '7') {
                $user = auth()->user();
                DB::table('uid')->insert([
                    'uid'=>$request->uid,
                    'user_id'=>auth()->user()->id
                    ]);
                $token = $user->createToken('token');
                return response()->json(['token' => $token->plainTextToken, 'error' => '','name' =>auth()->user()->name,'company_id'=>auth()->user()->company_id], 200, [], JSON_UNESCAPED_UNICODE);
            } else {
                return response()->json([
                    'error' => 'غير مسموح لك بالدخول',
                    'status' => 200
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function check(Request $request)
    {
        $staus = DB::table('uid')->select('active')->where('uid', '=', $request->uid)->get()[0]->active;
        if ($staus == '1') {
        } elseif ($staus == '0') {
            return response()->json(['error' => 'برجاء التواصل مع الادمن لاعاده تفعيل حسابك'], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
