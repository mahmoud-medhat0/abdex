<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class causereturn extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function addcausereturn()
    {
        session()->flash('active', 'addcausereturn');
        return view('orders.causesreturn.add');
    }
    public function storecausereturn(Request $request)
    {
        $validate = ['cause' => ['required', 'max:255']];
        $request->validate($validate);
        DB::table('causes_return')->insert(
            ['cause' => $request['cause']]
        );
        DB::table('causes_return_history')->insert([
            'action' => 'add',
            'new'=>json_encode(DB::table('causes_return')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->back()->with('success', 'cause return added successfully');
    }
    public function listcausesreturn()
    {
        session()->flash('active', 'listcausesreturn');
        $causes = DB::table('causes_return')->select('*')->get();
        return view('orders.causesreturn.all', compact('causes'));
    }
    public function editcausereturn($id)
    {
        $cause = DB::table('causes_return')->select('*')->where('id', '=', $id)->get()['0'];
        return view('orders.causesreturn.edit', compact('cause'));
    }
    public function updatecausereturn(Request $request)
    {
        $id = $request['id'];
        $validate = [
            'cause' => ['required', 'max:255']
        ];
        $request->validate($validate);
        $cause = DB::table('causes_return')->select('*')->where('id', '=', $id);
        $causeold = $cause->get();
        $cause->update([
            'cause' => $request['cause']
        ]);
        DB::table('causes_return_history')->insert([
            'action' => 'edit',
            'old' => json_encode($causeold[0],JSON_UNESCAPED_UNICODE),
            'new' => json_encode($cause->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        $cause = DB::table('causes_return')->select('*')->where('id', '=', $id)->get()['0'];
        return redirect()->back()->with('success', 'order cause return updated successfully')->with('cause', $cause);
    }
    public function deletecausereturn($id)
    {
        $cause = DB::table('causes_return')->select('*')->where('id', '=', $id);
        $causeold = $cause->get();
        $cause->delete();
        DB::table('causes_return_history')->insert([
            'action' => 'delete',
            'old' => json_encode($causeold[0],JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        $causes = DB::table('causes_return')->select('*')->get();
        return redirect()->back()->with('success', 'order cause return deleted successfully')->with('cause', $causes);
    }
}
