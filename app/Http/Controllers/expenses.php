<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class expenses extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function expenses()
    {
        session()->flash('active','expenses');
        $expenses = DB::table('company_expenses')->select('*')->get();
        return view('expenses.all',compact('expenses'));
    }
    public function expenses_add()
    {
        session()->flash('active','expenses_add');
        return view('expenses.add');
    }
    public function expenses_store(Request $request)
    {
        $validate=[
            'name'=>['required'],
            'cost'=>['required'],
            'type_out'=>['required','in:0,1,2,3']
        ];
        $request->validate($validate);
        DB::table('company_expenses')->insert([
            'name'=>$request['name'],
            'cost'=>$request['cost'],
            'type_out'=>$request['type_out']
        ]);
        $after =DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
        DB::table('keep_money')->insert([
            'moneyold'=>$after,
            'moneyafter'=>$after-$request['cost'],
            'user_id'=>Auth::user()->id
        ]);
        $after =DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
            DB::table('profits')->insert([
                'moneyold'=>$after,
                'moneyafter'=>$after-$request['cost'],
            ]);
        return redirect()->back()->with('success','expenses added successfull');
    }
    public function profits_out()
    {
        session()->flash('active','profits_out');
        return view('profits.add');
    }
    public function profits_out_store(Request $request)
    {
        $validate = [
            'name'=>['required'],
            'cost'=>['required','int']
        ];
        $request->validate($validate);
        $latest =0+DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
        DB::table('keep_money')->insert([
            'moneyold'=>$latest,
            'moneyafter'=>$latest+$request['cost'],
            'user_id'=>Auth::user()->id
        ]);
        $latest1 = DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
        DB::table('profits')->insert([
            'moneyold'=>$latest,
            'moneyafter'=>$latest1+$request['cost'],
        ]);
        $latest2 = DB::table('profits_out')->latest('created_at')->get()[0]->moneyafter;
        DB::table('profits_out')->insert([
            'moneyold'=>$latest,
            'moneyafter'=>$latest2+$request['cost'],
            'name'=>$request['name']
        ]);
        return redirect()->back()->with('success','تمت اضافه الربح الخارجي بنجاح');
    }
}
