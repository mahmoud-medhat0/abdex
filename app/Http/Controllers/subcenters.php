<?php

namespace App\Http\Controllers;

use App\Rules\vaildcenter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\subcentersimport;
use Illuminate\Support\Facades\DB;

class subcenters extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function readsubcenter()
    {
        session()->flash('active','readsubcenter');
        $subcenters =DB::table('sub_center')->selectRaw('sub_center.id')->selectRaw('sub_center.name')->join('centers','sub_center.id_center','=','centers.id')->selectRaw('centers.center_name')->get();
        return view('centers.sub_centers.all',compact('subcenters'));
    }
    public function editsubcenter($id)
    {
        $data =DB::table('sub_center')->select('*')->where('id','=',$id)->get()['0'];
        $centers = DB::table('centers')->select('*')->get();
        return view('centers.sub_centers.edit')->with('data',$data)->with('centers',$centers);
    }

    public function addsubcenter()
    {
        session()->flash('active','subcenter_add');
        $centers = DB::table('centers')->select('*')->get();
        return view('centers.sub_centers.add',compact('centers'));
    }
    public function storesubcenter(Request $request)
    {
        $validate=[
            'name' =>['required','max:255'],
            'center' => ['required','max:255',new vaildcenter],
        ];
        request()->validate($validate);
        DB::table('sub_center')->insert([
            'id_center'=>$request['center'],
            'name' => $request['name']
        ]);
        $subcenter = DB::table('sub_center')->latest('created_at')->get();
        DB::table('sub_centers_history')->insert([
            'action'=>'add',
            'new'=>json_encode($subcenter[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $centers = DB::table('centers')->select('*')->get();
        return redirect()->back()->with('success','sub center addedd successfully')->with('centers',$centers);
    }
    public function updatesubcenter(Request $request)
    {
        $validate=[
            'name' =>['required'],
            'center'=>['required','max:255',new vaildcenter]
        ];
        $request->validate($validate);
        $subcenter=DB::table('sub_center')->where('id','=',$request['id']);
        $old = $subcenter->get();
        $subcenter->update([
            'id_center'=>$request['center'],
            'name'=>$request['name']
        ]);
        DB::table('sub_centers_history')->insert([
            'action'=>'add',
            'old'=>json_encode($old[0],JSON_UNESCAPED_UNICODE),
            'new'=>json_encode($subcenter->get[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','sub center updated successfully');
    }
    public function deletesubcenter($id)
    {
        $subcenter = DB::table('sub_center')->where('id','=',$id);
        DB::table('sub_centers_history')->insert([
            'action'=>'delete',
            'old'=>json_encode($subcenter->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $subcenter->delete();
        $subcenters =DB::table('sub_center')->selectRaw('sub_center.id')->selectRaw('sub_center.name')->join('centers','sub_center.id_center','=','centers.id')->selectRaw('centers.center_name')->get();
        return redirect()->back()->with('success','center deleted successfully')->with('subcenters',$subcenters);
    }
    public function excelsubcenter()
    {
        session()->flash('active','excelsubcenter');
        $centers = DB::table('centers')->select('*')->get();
        return view('centers.sub_centers.addexcel',compact('centers'));
    }
    public function excelcenterstore()
    {
        Excel::import(new subcentersimport,request()->file('sheet'));
        $centers = DB::table('centers')->select('*')->get();
        return redirect()->back()->with('success','data imported successful')->with('centers',$centers);
    }

}
