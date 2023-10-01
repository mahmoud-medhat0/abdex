<?php

namespace App\Http\Controllers;

use App\Rules\validcause;
use App\Rules\validsatus;
use App\Rules\vaildcenter;
use App\Rules\validdelegate;
use Illuminate\Http\Request;
use App\Rules\ValidOrderLogic;
use App\Exports\SuppliesExport;
use App\Rules\valididpersonfin;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Center;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Imports\orderssearch;
use Illuminate\Validation\Rules\File;

class accountant extends Controller
{
    public $money = 0;
    public function __construct()
    {
        session()->flash('parent', 'accountant');
        $sum_accounts = DB::table('accounts_financial')->where('confirm', '0')->sum('creditor');
        $sum_accounts_debtor = DB::table('accounts_financial')->where('confirm', '0')->sum('debtor');
        $sum_agents = DB::table('account_stament_agents')->where('confirm', '0')->sum('payed');
        $this->money += 0 + ($sum_accounts - $sum_accounts_debtor) + $sum_agents;
        return $this->middleware(['auth', 'isaccountant']);
    }

    public function index()
    {
        session()->flash('active', 'accountant');
        $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '0')->where('company_supply','0')->orderBy('created_at', 'desc')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $printhistory = DB::table('print_history');
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $orders);
        return view('accountant.all')
            ->with('printhistory',$printhistory)
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('money', $this->money);
    }
    public function add()
    {
        session()->flash('active', 'accountant_add_personfin');
        return view('accountant.financial.persons.add')->with('money', $this->money);
    }
    public function store(Request $request)
    {
        $validate = [
            'name' => ['required'],
            'rank' => ['required']
        ];
        $request->validate($validate);
        DB::table('persons_accounts_financial')->insert([
            'name' => $request['name'],
            'rank' => $request['rank']
        ]);
        DB::table('persons_accounts_financial_history')->insert([
            'action' => 'add',
            'new' => json_encode(DB::table('persons_accounts_financial')->latest('created_at')->get(), JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->back()->with('success', 'تمت اضافه الشخص بنجاح الى كشف الحساب')->with('money', $this->money);
    }
    public function index_persons()
    {
        session()->flash('active', 'accountant_pesronsfin');
        $persons = DB::table('persons_accounts_financial')->select('*')->get();
        $dues = array();
        $payed = array();
        foreach ($persons as $person) {
            $due = DB::table('accounts_financial')->select('creditor')->where('person_id', '=', $person->id)->get();
            $dues[$person->id] = 0;
            $pay = DB::table('accounts_financial')->select('debtor')->where('person_id', '=', $person->id)->get();
            $payed[$person->id] = 0;
            $orderscost[$person->id] = 0;
            foreach ($due as $key) {
                $dues[$person->id] += $key->creditor;
            }
            foreach ($pay as $key) {
                $payed[$person->id] += $key->debtor;
            }
        }
        return view('accountant.financial.persons.all')->with('money', $this->money)->with('persons', $persons)->with('dues', $dues)->with('payed', $payed);
    }
    public function add_finance(Request $request)
    {
        session()->flash('active', 'accountant_add_finance');
        $persons = DB::table('persons_accounts_financial')->select('id', 'name')->get();
        return view('accountant.financial.accountants.add')->with('persons', $persons)->with('money', $this->money);
    }
    public function store_finance(Request $request)
    {
        $validate = [
            'person' => ['required', new valididpersonfin],
            'creditor' => ['required', 'int'],
            'debtor' => ['required', 'int'],
            'cause' => ['required']
        ];
        $request->validate($validate);
        DB::table('accounts_financial')->insert([
            'person_id' => $request['person'],
            'creditor' => $request['creditor'],
            'debtor' => $request['debtor'],
            'cause' => $request['cause'],
            'confirm' => '0',
            'user_id' => Auth()->user()->id
        ]);
        $total = $request['debtor'] - $request['creditor'];
        $latest = DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
        DB::table('keep_money')->insert([
            'moneyold' => $latest,
            'moneyafter' => $latest + $total,
            'user_id' => Auth::user()->id
        ]);
        return redirect()->back()->with('success', 'تمت الاضافه بنجاح')->with('money', $this->money);
    }
    public function person_acontant($id)
    {
        $name = DB::table('persons_accounts_financial')->select('name')->where('id', '=', $id)->get()[0]->name;
        $accountants = DB::table('accounts_financial')->select('*')->where('person_id', '=', $id)->get();
        return view('financial.accountants.history')->with('accountants', $accountants)->with('name', $name)->with('money', $this->money);
    }
    public function addorders()
    {
        session()->flash('active', 'accountant_addorders');
        $companies = DB::table('companies')->select('id', 'name')->get();
        return view('orders.add', compact('companies'))->with('money', $this->money);
    }
    public function new(Request $request)
    {
        session()->flash('active', 'accountant_neworders');
        $companies = DB::table('companies')->select('*')->get();
        $centers = DB::table('centers')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        return view('orders.addmanual')->with('companies', $companies)->with('centers', $centers)->with('agents', $agents)->with('delegates', $delegates)->with('money', $this->money);
    }
    public function orders_history()
    {
        session()->flash('active', 'accountant_orders_history');
        $data = DB::table('orders_history')->select('*')->where('action', '=', 'update by delegate')->orWhere('action', '=', 'update')
            ->join('users', 'users.id', '=', 'orders_history.user_id')->selectRaw('users.name')
            ->selectRaw('orders_history.id AS sid')->get();
        $oldvalues = array();
        $newvalues = array();
        foreach ($data as $order => $value) {
            $old = json_decode($value->old);
            $new = json_decode($value->new);
            $oldvalues[$value->sid] = array();
            $newvalues[$value->sid] = array();
            foreach ($old as $old1 => $value1) {
                // dd($old1,$value1);
                if ($old->$old1 != $new->$old1) {
                    $oldvalues[$value->sid][$old1] = array();
                    // $oldvalues[$value->sid][$old1][$old->$old1] = array();
                    $newvalues[$value->sid][$old1] = array();
                    // $newvalues[$value->sid][$old1][$new->$old1] = array();
                    array_push($oldvalues[$value->sid][$old1], $old->$old1);
                    array_push($newvalues[$value->sid][$old1], $new->$old1);
                }
            }
        }
        return view('history.orders')->with('data', $data)->with('oldvalues', $oldvalues)->with('newvalues', $newvalues)->with('money', $this->money);
    }
    public function history_arcieve()
    {
        session()->flash('active', 'accountant_history_arcieve');
        $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '1')->get();
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
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $orders);
        return view('history.archieve')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('money', $this->money);
    }
    public function supplies_newd()
    {
        session()->flash('active', 'accountant_supplies_new');
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
        return view('accountant.supplies.add_d', compact('agents'))->with('payed', $payed)->with('dues', $dues)->with('money', $this->money);
    }
    public function hold_supplies()
    {
        session()->flash('active', 'accountant_hold_supplies');
        $supplies = DB::table('account_stament_agents')->select('account_stament_agents.id', 'account_stament_agents.confirm', 'account_stament_agents.payed')->where('account_stament_agents.confirm', '=', '0')
            ->join('users AS dd', 'dd.id', '=', 'account_stament_agents.user_id')->selectRaw('dd.name')->get();
        return view('accountant.supplies.hold')->with('supplies', $supplies)->with('money', $this->money);
    }
    public function h_supplies($agentid)
    {
        $histories = DB::table('account_stament_agents AS dd')->select('dd.id', 'dd.agent_id', 'dd.payed', 'late', 'dd.total', 'dd.excel', 'dd.created_at')->where('dd.agent_id', '=', $agentid)
            ->join('users AS agents', 'agents.id', '=', 'dd.agent_id')->selectRaw('agents.name')->get();
        $name = DB::table('users')->select('name')->where('id', '=', $agentid)->get()[0]->name;
        return view('supplies.history_supllies', compact('histories'))->with('name', $name)->with('money', $this->money);
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
            ->where('delegate_id', '=', $id)
            ->orWhere('status_id', '=', '8')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)
            ->orWhere('status_id', '=', '7')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)
            ->orWhere('status_id', '=', '4')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)
            ->orWhere('status_id', '=', '5')
            ->where('delegate_supply', '=', '0')
            ->where('delegate_id', '=', $id)
            ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state')
            ->selectRaw('orders.address')->selectRaw('orders.cost')
            ->join('companies', 'orders.id_company', '=', 'companies.id')->selectRaw('companies.name AS company_name')
            ->join('users as agents', 'agents.id', '=', 'orders.delegate_id')->selectRaw('agents.name AS agent_name')
            ->get();
        session()->flash('orders', $orders);
        return view('accountant.supplies.all')->with('agentname', $agentname)->with('id', $id)->with('money', $this->money);
    }
    public function supplies_supply(Request $request)
    {
    $r = array();

    foreach ($request->all() as $key => $value) {
        if (strpos($key, "checkbox-") === 0) {
            $checkboxes[] = $value;
        }
    }
        foreach ($r as $r1 => $value) {
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
        Excel::download(new SuppliesExport, 'تقفيل_' . $request['name'] . '_' . date("Y-m-d h:i") . '.xlsx');
        $r = $request->except('_token', 'example1_length', 'balance', 'payed', 'name', 'id', 'total');
        foreach ($r as $r1 => $value) {
            DB::table('orders')->where('id', '=', $value)->update([
                'delegate_supply' => 1,
                'delegate_supply_date' => now()
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
        return redirect()->route('accountant_supplies_newd')->with('agents', $agents)->with('success', 'saved successfully')->with('dues', $dues)->with('payed', $payed)->with('money', $this->money);
    }
    public function edit($id)
    {
        $order = DB::table('orders')->select('*')->where('id', '=', $id)->get()[0];
        $companies = DB::table('companies')->select('*')->get();
        $centers = DB::table('centers')->select('*')->get();
        $delegates = DB::table('users')->select('id', 'name')->where('rank_id', '=', '9')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        return view('accountant.edit')
            ->with('order', $order)
            ->with('companies', $companies)
            ->with('centers', $centers)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('money', $this->money);
    }
    public function update(Request $request)
    {
        $validate['id'] = ['required', new ValidOrderLogic];
        $request->validate($validate);
        $id = request()->input('id');
        $order = DB::table('orders')->select('*')->where('id', '=', $id)->get()[0];
        $orderupdate = DB::table('orders')->select('*')->where('id', '=', $id);
        $orderold = $orderupdate->get();
        if ($request['phone'] != null) {
            if ($request['phone'] != "none") {
                $orderupdate->update([
                    'phone' => $request['phone']
                ]);
            }
        } else {
            $orderupdate->update([
                'phone' => 'none'
            ]);
        }
        if ($request['phone2'] != null) {
            if ($request['phone2'] != "none") {
                $orderupdate->update([
                    'phone2' => $request['phone2']
                ]);
            }
        } else {
            $orderupdate->update([
                'phone2' => 'none'
            ]);
        }
        // if ($request['agent_id'] != "none"|| $request['agent_id']!='agent'||$request['agent_id']!='وكيل') {
        //     $validate['agent_id'] = [new validagent];
        //     request()->validate($validate);
        //     $orderupdate->update([
        //         'agent_id' => $request['agent_id']
        //     ]);
        // } else {
        //     $orderupdate->update([
        //         'agent_id' => null
        //     ]);
        // }
        if ($request['delegate_id'] != "none") {
            $validate['delegate_id'] = [new validdelegate];
            request()->validate($validate);
            $orderupdate->update([
                'delegate_id' => $request['delegate_id']
            ]);
        } else {
            $orderupdate->update([
                'delegate_id' => null
            ]);
        }
        if ($request['address'] == null) {
            $orderupdate->update(['address' => "none"]);
        } else {
            $orderupdate->update(['address' => $request['address']]);
        }
        if ($request['cost'] == null) {
            $orderupdate->update([
                'cost' => 'none'
            ]);
        } else {
            $orderupdate->update(['cost' => $request['cost']]);
        }
        if ($request['cost'] == null) {
            $orderupdate->update([
                'cost' => 'none'
            ]);
        } else {
            $orderupdate->update(['cost' => $request['cost']]);
        }
        if ($request['notes'] == null) {
            $orderupdate->update([
                'notes' => 'none'
            ]);
        } else {
            $orderupdate->update(['notes' => $request['notes']]);
        }
        if ($request['special_intructions'] == null) {
            $orderupdate->update([
                'special_intructions' => 'none'
            ]);
        } else {
            $orderupdate->update(['special_intructions' => $request['special_intructions']]);
        }
        if ($request['name_product'] == null) {
            $orderupdate->update([
                'name_product' => 'none'
            ]);
        } else {
            $orderupdate->update(['name_product' => $request['name_product']]);
        }
        if ($request['sender'] == null) {
            $orderupdate->update([
                'sender' => 'none'
            ]);
        } else {
            $orderupdate->update(['sender' => $request['sender']]);
        }
        if ($request['weghit'] == null) {
            $orderupdate->update([
                'weghit' => 'none'
            ]);
        } else {
            $orderupdate->update(['weghit' => $request['weghit']]);
        }
        if ($request['open'] == null) {
            $orderupdate->update([
                'open' => 'none'
            ]);
        } else {
            $orderupdate->update(['open' => $request['open']]);
        }
        if ($request['status_id'] != "none") {
            $validate['status_id'] = ['required', new validsatus];
            request()->validate($validate);
            $orderupdate->update(['status_id' => $request['status_id']]);
        } else {
            $orderupdate->update(['status_id' => null]);
        }
        if ($request['cause_id'] != "none") {
            $validate['cause_id'] = ['required', new validcause];
            request()->validate($validate);
            $orderupdate->update(['cause_id' => $request['cause_id']]);
        } else {
            $orderupdate->update(['cause_id' => null]);
        }
        if ($request['order_locate'] != "none") {
            $validate['order_locate'] = ['in:0,1,2,3,4'];
            request()->validate($validate);
            $orderupdate->update([
                'order_locate' => $request['order_locate']
            ]);
        } else {
            $orderupdate->update(['order_locate' => null]);
        }
        if ($request['identy_number'] == null) {
            $orderupdate->update([
                'identy_number' => 'none'
            ]);
        } else {
            $orderupdate->update(['identy_number' => $request['identy_number']]);
        }
        if ($request['company_supply'] == null || $request['company_supply'] == '0') {
            $orderupdate->update([
                'company_supply' => 0,
            ]);
        } else {
            $validate['company_supply'] = ['in:0,1'];
            $request->validate($validate);
            $orderupdate->update([
                'company_supply' => $request['company_supply'],
                'company_supply_date' => now()
            ]);
        }
        if ($request['delegate_supply'] == null || $request['delegate_supply'] == '0') {
            $orderupdate->update([
                'delegate_supply' => 0,
            ]);
        } else {
            $validate['delegate_supply'] = ['in:0,1'];
            $request->validate($validate);
            $orderupdate->update([
                'delegate_supply' => $request['delegate_supply'],
                'delegate_supply_date' => now()
            ]);
        }
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'edit',
            'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
            'new' => json_encode($orderupdate->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->route('accountant')->with('order', $order)->with('success', 'order updated successful')->with('money', $this->money);
    }
    public function orders_delegate()
    {
        $centers = Center::all();
        $companies = Company::all();
        $agents = User::where('rank_id','9')->get() ;
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        if (session()->get('orders') != null) {
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        return view('accountant.orders_delegate')->with('centers', $centers)
        ->with('companies', $companies)
        ->with('agents', $agents)
        ->with('delegates', $delegates)
        ->with('states', $states)
        ->with('causes', $causes)
        ->with('police_duplicate', $police_duplicate)
        ->with('delayes',$delayes)
        ->with('money',$this->money);
    }
    public function orders_delegate_search($id)
    {
        session()->flash('active','orders.delegates');
        $centers = Center::all();
        $companies = Company::all();
        $agents = User::where('rank_id','9')->get() ;
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        if (session()->get('orders') != null) {
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        if ($id){
            $orders = DB::table('orders')->where('delegate_id',$id)->get();
        }else{
            $orders = DB::table('orders')->get();
        }
        session()->flash('orders', $orders);
        return view('accountant.orders_delegate')
        ->with('centers', $centers)
        ->with('companies', $companies)
        ->with('agents', $agents)
        ->with('delegates', $delegates)
        ->with('states', $states)
        ->with('causes', $causes)
        ->with('police_duplicate', $police_duplicate)
        ->with('delayes',$delayes)
        ->with('id',$id)
        ->with('money',$this->money);
    }
    public function indexcompanies($id){
        session()->flash('active', 'orders_all');
        $orders = DB::table('orders')->where('id_company',$id)->where('order_locate','0')->where('on_archieve', '=', '0')->orderBy('created_at', 'desc')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $printhistory = DB::table('print_history');
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        session()->flash('orders', $orders);
        return view('accountant.all')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes',$delayes)
            ->with('printhistory',$printhistory)
            ->with('money',$this->money);
    }
    public function orderssearch()
    {
        if(request()->method() === 'GET'){
            $orders = [];
            foreach(Cache::get('orders') as $order){
                $orders[$order->id] = DB::table('orders')->where('id',$order->id)->select('*')->get()[0];
            }
            // dd($orders);
            session()->flash('orders',$orders);
            $companies = DB::table('companies')->select('*')->get();
            $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
            $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
            $states = DB::table('order_state')->select('*')->get();
            $causes = DB::table('causes_return')->select('*')->get();
            $printhistory = DB::table('print_history');
            $police_duplicate = array();
            $polices = array();
            $delayes = DB::table('causes_delay')->select('*')->get();
        return view('accountant.all')
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes',$delayes)
            ->with('printhistory',$printhistory)
            ->with('money',$this->money);
        }
        request()->validate([
            'sheet' => ['required', File::types(['xls', 'xlsx'])]
        ]);
        Excel::import(new orderssearch, request()->file('sheet'));
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $printhistory = DB::table('print_history');
        $police_duplicate = array();
        $polices = array();
        $delayes = DB::table('causes_delay')->select('*')->get();
        if (session()->get('orders') != null) {
            Cache::put('orders', session()->get('orders'), 99999); // $minutes indicates the duration for which the data should be stored
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        return view('accountant.all')
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes',$delayes)
            ->with('printhistory',$printhistory)
            ->with('money',$this->money);

    }
}
