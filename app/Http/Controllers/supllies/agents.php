<?php

namespace App\Http\Controllers\supllies;

use Illuminate\Http\Request;
use App\Exports\SuppliesExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class agents extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function supplies_newd()
    {
        session()->flash('active', 'supplies_new');
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '9')->get();
        $dues = array();
        $payed = array();
        foreach ($agents as $agent) {
            $due = DB::table('account_stament_agents')->select('late')->where('agent_id', '=', $agent->id)->get();
            $dues[$agent->id] = 0;
            $pay = DB::table('account_stament_agents')->select('payed')->where('agent_id', '=', $agent->id)->get();
            $payed[$agent->id] = 0;
            $orderscost[$agent->id] = 0;
            foreach ($due as $key) {
                $dues[$agent->id] += $key->late;
            }
            foreach ($pay as $key) {
                $payed[$agent->id] += $key->payed;
            }
        }
        return view('supplies.add_d', compact('agents'))->with('payed', $payed)->with('dues', $dues);
    }
    public function supplies_stored($id)
    {
        $agentname = DB::table('users')->select('name')->where('id', '=', $id)->get()[0]->name;
        $orders = DB::table('orders')->select('agent_id', 'orders.id', 'orders.id_company', 'id_police', 'name_client', 'phone', 'phone2', 'delegate_id', 'orders.delegate_supply')
            ->where('delegate_id', '=', $id)
            ->where('delegate_supply', '=', '0')
            ->where('status_id', '=', '1')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)
            ->orWhere('status_id', '=', '2')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)->where('order_locate','2')
            ->orWhere('status_id', '=', '8')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)->where('order_locate','2')
            ->orWhere('status_id', '=', '7')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)->where('order_locate','2')
            ->orWhere('status_id', '=', '4')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)->where('order_locate','2')
            ->orWhere('status_id', '=', '5')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)->where('order_locate','2')
            ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state')
            ->selectRaw('orders.address')->selectRaw('orders.cost')
            ->join('companies', 'orders.id_company', '=', 'companies.id')->selectRaw('companies.name AS company_name')
            ->join('users as agents', 'agents.id', '=', 'orders.delegate_id')->selectRaw('agents.name AS agent_name')
            ->selectRaw('agents.commision')
            ->get();
        session()->flash('orders', $orders);
        return view('supplies.all')->with('agentname', $agentname)->with('id', $id);
    }
    public function supplies_supply(Request $request)
    {
        $r = $request->except('_token', 'example1_length', 'balance', 'payed', 'name', 'id', 'total','commission');
        foreach ($request->all() as $inputName => $inputValue) {
            if (strpos($inputName, 'checkbox-') === 0) {
                $id = str_replace('checkbox-', '', $inputName);
                $commissionName = 'checkbox-' . $id;
                $commissionValue = $request->input($commissionName);
                $checkboxValues[$id] = $commissionValue;
            }
        }
        if (!isset($checkboxValues)) {
            return redirect()->back()->with('error','لا يمكن انشاء توريدة فارغة');
        }
        foreach ($checkboxValues as $r1 => $value) {
            $order = DB::table('orders')->where('id', '=', $value);
            if ($order->get()[0]->delegate_supply=='1') {
                return redirect()->back()->with('error','لقد تم التوريد من قبل');
            }
        }
        $validate = [
            'payed' => ['required']
        ];
        $request->validate($validate);
        DB::table('account_stament_agents')->insert([
            'agent_id' => $request['id'],
            'payed' => $request['payed'],
            'total' => $request['total'],
            'late' => $request['total'] - $request['payed'],
            'user_id' => Auth::user()->id
        ]);
        $id = DB::table('account_stament_agents')->select('id')->where('agent_id', '=', $request['id'])->latest('created_at')->get()[0]->id;
        $filepath = 'agents/'.$request['id'].'/'.$request['id'].'_'.$id.'.xlsx';
        Excel::store(new SuppliesExport, 'agents/'.$request['id'].'/'.$request['id'].'_'.$id.'.xlsx','public');
        DB::table('account_stament_agents')->select('*')->where('id', '=', $id)->update([
            'excel' => $filepath
        ]);
        date_default_timezone_set('Africa/Cairo');
        $path = storage_path('app/public/' . $filepath);
        // Storage::download($path,'تقفيل_' . $request['name'] . '_' . date("Y-m-d h:i") . '.xlsx');
        // Excel::download(new SuppliesExport, 'تقفيل_' . $request['name'] . '_' . date("Y-m-d h:i") . '.xlsx');
        $r = $request->except('_token', 'example1_length', 'balance', 'payed', 'name', 'id', 'total');
        foreach ($checkboxValues as $r1 => $value) {
            DB::table('orders')->where('id', '=', $value)->update([
                'delegate_supply' => 1,
                'delegate_supply_date' => now(),
                'delegate_commission'=>$request['commission_'.$id]
            ]);
        }
        if (Auth::user()->rank_id == '1') {
            DB::table('account_stament_agents')->latest('created_at')->update(['confirm' => 1]);
            $payedold = $request['payed'];
            $payed = 0 + $payedold;
            $latest = 0 + DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
            DB::table('keep_money')->insert([
                'moneyold' => $latest,
                'moneyafter' => $payed + $latest,
                'user_id' => Auth::user()->id
            ]);
            $latest0 = 0 + DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
            DB::table('profits')->insert([
                'moneyold' => $latest,
                'moneyafter' => $latest0 - $request['commission']
            ]);
        }
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $dues = array();
        $payed = array();
        foreach ($agents as $agent) {
            $due = DB::table('account_stament_agents')->select('late')->where('agent_id', '=', $agent->id)->get();
            $dues[$agent->id] = 0;
            $pay = DB::table('account_stament_agents')->select('payed')->where('agent_id', '=', $agent->id)->get();
            $payed[$agent->id] = 0;
            $orderscost[$agent->id] = 0;

            foreach ($due as $key) {
                $dues[$agent->id] += $key->late;
            }
            foreach ($pay as $key) {
                $payed[$agent->id] += $key->payed;
            }
        }
        return redirect()->route('supplies_newd')->with('agents', $agents)->with('success', 'saved successfully')->with('dues', $dues)->with('payed', $payed);
    }
    public function h_supplies($agentid)
    {
        $histories = DB::table('account_stament_agents AS dd')->select('dd.id', 'dd.agent_id', 'dd.payed', 'late', 'dd.total', 'dd.excel', 'dd.created_at')->where('dd.agent_id', '=', $agentid)
            ->join('users AS agents', 'agents.id', '=', 'dd.agent_id')->selectRaw('agents.name')->get();
        // dd($histories);
        $name = DB::table('users')->select('name')->where('id', '=', $agentid)->get()[0]->name;
        return view('supplies.history_supllies', compact('histories'))->with('name', $name);
    }
    public function hold_supplies()
    {
        session()->flash('active', 'hold_supplies');
        $supplies = DB::table('account_stament_agents')->select('account_stament_agents.id', 'account_stament_agents.confirm', 'account_stament_agents.payed')->where('account_stament_agents.confirm', '=', '0')
            ->join('users AS dd', 'dd.id', '=', 'account_stament_agents.user_id')->selectRaw('dd.name')->get();
        return view('supplies.hold')->with('supplies', $supplies);
    }
    public function confirm_supplies(Request $request)
    {
        $checks = $request->except('_token');
        foreach ($checks as $r1 => $value) {
            DB::table('account_stament_agents')->where('id', '=', $value)->update([
                'confirm' => 1,
            ]);
            $payedold = DB::table('account_stament_agents')->where('id', '=', $value)->select('payed')->get()[0]->payed;
            $payed = 0 + $payedold;
            $latest = 0 + DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
            DB::table('keep_money')->insert([
                'moneyold' => $latest,
                'moneyafter' => $payed + $latest,
                'user_id' => Auth::user()->id
            ]);
        }
        return redirect()->back()->with('success', 'supplies confirmed');
    }
    public function sheet($id)
    {
        $path = DB::table('account_stament_agents')->where('id',$id)->select('excel')->get();
        if (count($path)>0 && Storage::disk('public')->exists($path[0]->excel)) {
            return Storage::disk('public')->download($path[0]->excel);
        }
        abort(404, 'File not found');
    }
}
