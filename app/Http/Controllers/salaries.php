<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class salaries extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function salaries()
    {
        session()->flash('active','salaries');
        $salaries =DB::table('salaries')
        ->selectRaw('salaries.id')
        ->selectRaw('salaries.id_user')
        ->selectRaw('salaries.salary')
        ->selectRaw('salaries.discount')
        ->selectRaw('salaries.notes')
        ->selectRaw('salaries.done')
        ->join('users','salaries.id_user','=','users.id')->selectRaw('users.name')->get();
        return view('salaries.all',compact('salaries'));
    }
    public function edit($id)
    {
        $salary = DB::table('salaries')->select('id','discount','done')->where('id','=',$id)->get()[0];
        return view('salaries.edit',compact('salary'));
    }
    public function update(Request $request)
    {
        $id = $request['id'];
        $salary =DB::table('salaries')->select('discount','done')->where('id','=',$id);
        $validate['done'] = ['required','in:0,1'];
        $request->validate($validate);
        $salary->update([
            'discount' =>$request['discount'],
            'done' => $request['done']
        ]);
        $tt = $salary->select('salary','discount')->get()[0];
        $total = $tt->salary-$tt->discount;
        if ($request['done']=='1'){
            $after =DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
            DB::table('keep_money')->insert([
                'moneyold'=>$after,
                'moneyafter'=>$after-$total,
                'user_id'=>Auth::user()->id
            ]);
            $after =DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
            DB::table('profits')->insert([
                'moneyold'=>$after,
                'moneyafter'=>$after-$total,
            ]);
        }
        $salary = DB::table('salaries')->select('id','discount','done')->where('id','=',$id)->get()[0];
        return redirect()->back()->with('salary',$salary)->with('success','salary updated successfully');
    }

}
