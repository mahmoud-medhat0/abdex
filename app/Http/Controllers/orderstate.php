<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class orderstate extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function addorderstate()
    {
        session()->flash('active','addorderstate');
        return view('orders.orderstate.add');
    }
    public function storeorderstate(Request $request)
    {
        $validate = [
            'state' => ['required','max:255']
        ];
        $request->validate($validate);
        DB::table('order_state')->insert(['state' => $request['state']]);
        DB::table('order_state_history')->insert([
            'action'=>'add',
            'new'=>json_encode(DB::table('order_state')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','order state added successfully');
    }
    public function listorderstate()
    {
        session()->flash('active','listorderstate');
        $states = DB::table('order_state')->select('*')->get();
        return view('orders.orderstate.all',compact('states'));
    }
    public function editorderstate($id)
    {
        $state = DB::table('order_state')->select('*')->where('id','=',$id)->get()['0'];
        return view('orders.orderstate.edit',compact('state'));
    }
    public function updateorderstate(Request $request)
    {
        $id =$request['id'];
        $validate = [
            'state' => ['required','max:255']
        ];
        $request->validate($validate);
        $state = DB::table('order_state')->select('*')->where('id','=',$id);
        $stateold = $state->get();
        $state->update([
            'state' =>$request['state']
        ]);
        DB::table('order_state_history')->insert([
            'action'=>'update',
            'old'=>json_encode($stateold[0],JSON_UNESCAPED_UNICODE),
            'new'=>json_encode($state->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','order state updated successfully');
    }
    public function deleteorderstate($id)
    {
        $state = DB::table('order_state')->select('*')->where('id','=',$id);
        DB::table('order_state_history')->insert([
            'action'=>'delete',
            'old'=>json_encode($state->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $state->delete();
        $states = DB::table('order_state')->select('*')->get();
        return redirect()->back()->with('success','order state deleted successfully')->with('states',$states);
    }
    // order states
}
