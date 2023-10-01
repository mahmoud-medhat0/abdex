<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class causedelay extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function addcause()
    {
        session()->flash('active', 'addcausedelay');
        return view('orders.causesdelay.add');
    }
    public function storecause(Request $request)
    {
        $validate = ['cause' => ['required', 'max:255']];
        $request->validate($validate);
        DB::table('causes_delay')->insert(
            ['cause' => $request['cause']]
        );
        DB::table('causes_delay_history')->insert([
            'action' => 'add',
            'new'=>json_encode(DB::table('causes_delay')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->back()->with('success', 'cause delay added successfully');
    }
    public function listcauses()
    {
        session()->flash('active', 'listcausesdelay');
        $causes = DB::table('causes_delay')->select('*')->get();
        return view('orders.causesdelay.all', compact('causes'));
    }
    public function editcause($id)
    {
        $cause = DB::table('causes_delay')->select('*')->where('id', '=', $id)->get()['0'];
        return view('orders.causesdelay.edit', compact('cause'));
    }
    public function updatecause(Request $request)
    {
        $id = $request['id'];
        $validate = [
            'cause' => ['required', 'max:255']
        ];
        $request->validate($validate);
        $cause = DB::table('causes_delay')->select('*')->where('id', '=', $id);
        $causeold = $cause->get();
        $cause->update([
            'cause' => $request['cause']
        ]);
        DB::table('causes_delay_history')->insert([
            'action' => 'edit',
            'old' => json_encode($causeold[0],JSON_UNESCAPED_UNICODE),
            'new' => json_encode($cause->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        $cause = DB::table('causes_delay')->select('*')->where('id', '=', $id)->get()['0'];
        return redirect()->back()->with('success', 'order cause delay updated successfully')->with('cause', $cause);
    }
    public function deletecause($id)
    {
        $cause = DB::table('causes_delay')->select('*')->where('id', '=', $id);
        $causeold = $cause->get();
        $cause->delete();
        DB::table('causes_delay_history')->insert([
            'action' => 'delete',
            'old' => json_encode($causeold[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        $causes = DB::table('causes_delay')->select('*')->get();
        return redirect()->back()->with('success', 'order cause delay deleted successfully')->with('cause', $causes);
    }
}
