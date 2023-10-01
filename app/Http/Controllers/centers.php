<?php

namespace App\Http\Controllers;

use App\Rules\validagent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class centers extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function add(Request $request)
    {
        session()->flash('active','center_add');
        $governorates = DB::table('governorates')->select('*')->get();
        $agents = DB::table('users')->select('id','name')->where('rank_id','=','8')->get();
        return view('centers.add',compact('governorates','agents'));
    }
    public function store(Request $request)
    {
        $validate = [
            'name'=>['required','max:255'],
            'agentid' => ['required','max:255',new validagent],
            'governorate' => ['required','exists:governorates,id'],
        ];
        $request->validate($validate);
        DB::table('centers')->insert([
            'center_name'=>$request['name'],
            'governate_id'=>$request['governorate'],
            'id_agent'=>$request['agentid']
        ]);
        $center = DB::table('centers')->latest('created_at')->get();
        DB::table('centers_history')->insert([
            'action'=>'add',
            'new'=>json_encode($center[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->route('centers')->with('success','center addedd successfully');
    }

    public function read(Request $request)
    {
        session()->flash('active','centers');
        $centers = DB::table('centers')->selectRaw('centers.id')->selectRaw('centers.center_name')->join('governorates','centers.governate_id','=','governorates.id')->selectRaw('governorate_name_ar')->selectRaw('governorate_name_en')->join('users','users.id','=','centers.id_agent')->selectRaw('name')->get();
        return view('centers.all',compact('centers'));
    }
    public function edit($id)
    {
        $data = DB::table('centers')->select('*')->where('id','=',$id)->get()['0'];
        $governorates = DB::table('governorates')->select('*')->get();
        $agents = DB::table('users')->select('id','name')->where('rank_id','=','8')->get();
        return view('centers.edit')->with('governorates',$governorates)->with('agents',$agents)->with('data',$data);
    }
    public function update(Request $request)
    {
        $validate = [
            'name'=>['required','max:255'],
            'agentid' => ['required','max:255',new validagent],
            'governorate' => ['required','exists:governorates,id'],
        ];
        $request->validate($validate);
        $center = DB::table('centers')->where('id','=',$request['id']);
        $center->update([
            'center_name'=>$request['name'],
            'governate_id'=>$request['governorate'],
            'id_agent'=>$request['agentid']
        ]);
        $centernew = DB::table('centers')->where('id','=',$request['id'])->get();
        DB::table('centers_history')->insert([
            'action'=>'edit',
            'old'=>json_encode($center,JSON_UNESCAPED_UNICODE),
            'new'=>json_encode($centernew[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->route('centers')->with('success','center updated successfully');
    }
    public function delete($id)
    {
        DB::table('centers')->select('*')->where('id','=',$id)->delete();
        $centers = DB::table('centers')->selectRaw('centers.id')->selectRaw('centers.center_name')->join('governorates','centers.governate_id','=','governorates.id')->selectRaw('governorate_name_ar')->selectRaw('governorate_name_en')->join('users','users.id','=','centers.id_agent')->selectRaw('name')->get();
        return redirect()->back()->with('success','center deleted successfully')->with('centers',$centers);
    }
}
