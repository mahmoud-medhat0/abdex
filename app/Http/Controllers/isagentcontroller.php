<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class isagentcontroller extends Controller
{
    public function __construct()
    {
        session()->flash('parent','agent');
        return $this->middleware(['auth','isagent']);
    }
    public function index()
    {
        session()->flash('active','agent');
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('agent_id','=',Auth::user()->id)->orderBy('created_at', 'desc')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('agents_delegates')->where('agent_id', '=', Auth::user()->id)->join('users','users.id','=','agents_delegates.delegate_id')
        ->selectRaw('users.id')->selectRaw('users.name')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $orders);
        return view('agents.orders')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate);
    }
    public function history()
    {
        session()->flash('active','companyhistory');
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('agent_id','=',Auth::user()->id)->select('*')->get();
        $data = array();
        $ids = array();
        foreach ($orders as $order) {
            $record = DB::table('orders_history')->select('*')->where('order_id','=',$order->id)->where('action','=','update by delegate')->orWhere('action','=','update')
            ->join('users','users.id','=','orders_history.user_id')->selectRaw('users.name')
            ->selectRaw('orders_history.id AS sid')->get();
            array_push($data,$record);
        }
        $oldvalues = array();
        $newvalues = array();
        foreach ($data[0] as $order =>$value) {
            $old = json_decode($value->old);
            $new = json_decode($value->new);
            $oldvalues[$value->sid] = array();
            $newvalues[$value->sid] = array();
            foreach ($old as $old1=>$value1) {
                // dd($old1,$value1);
                if($old->$old1!=$new->$old1){
                    $oldvalues[$value->sid][$old1] = array();
                    // $oldvalues[$value->sid][$old1][$old->$old1] = array();
                    $newvalues[$value->sid][$old1] = array();
                    // $newvalues[$value->sid][$old1][$new->$old1] = array();
                    array_push($oldvalues[$value->sid][$old1],$old->$old1);
                    array_push($newvalues[$value->sid][$old1],$new->$old1);
                }
            }
        }
        return view('history.orders')->with('data', $data[0])->with('oldvalues', $oldvalues)->with('newvalues', $newvalues);
    }
    public function archieve()
    {
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('agent_id','=',Auth::user()->id)->select('*')->get();
        $data = array();
        $ids = array();
        foreach ($orders as $order) {
            $record = DB::table('orders_history')->select('*')->where('id','=',$order->id)->where('action','=','update by delegate')->orWhere('action','=','update')
            ->join('users','users.id','=','orders_history.user_id')->selectRaw('users.name')
            ->selectRaw('orders_history.id AS sid')->get();
            array_push($data,$record);
        }
        // session()->flash('active', 'history_arcieve');
        // $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '1')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($data as $order) {
            if (in_array($order['id_police'], $polices)) {
                array_push($police_duplicate, $order['id_police']);
            } else {
                array_push($polices, $order['id_police']);
            }
        }
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $data);
        return view('history.archieve')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate);

    }
}
