<?php

namespace App\Http\Controllers\supllies;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\companies_supplies;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class companies extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function companies_supplies()
    {
        session()->flash('active', 'companies_supplies');
        $companies = DB::table('companies')->select('id', 'name')->get();
        $dues = array();
        $payed = array();
        foreach ($companies as $company) {
            $due = DB::table('account_stament_companies')->select('late')->where('company_id', '=', $company->id)->get();
            $dues[$company->id] = 0;
            $pay = DB::table('account_stament_companies')->select('payed')->where('company_id', '=', $company->id)->get();
            $payed[$company->id] = 0;
            $orderscost[$company->id] = 0;
            foreach ($due as $key) {
                $dues[$company->id] += $key->late;
            }
            foreach ($pay as $key) {
                $payed[$company->id] += $key->payed;
            }
        }
        return view('supplies.companies.add_d')->with('companies', $companies)->with('dues', $dues)->with('payed', $payed);
    }
    public function companies_new($id)
    {
        $companyname = DB::table('companies')->select('id', 'name')->where('id', '=', $id)->get()[0]->name;
        $orders = DB::table('orders')
        ->where('id_company', $id)
        ->where('company_supply', 0)
        ->where('delegate_supply','1')
        ->where(function ($query) {
            $query->where('status_id', 1)
                ->orWhere('status_id', 2)
                ->orWhere('status_id', 8)
                ->orWhere('status_id', 7)
                ->orWhere('status_id', 4)
                ->orWhere('status_id', 5);
        })->whereNot('status_id','3')->whereNot('status_id','9')
        ->select('orders.id','id_police', 'name_client', 'cost', 'delegate_supply', 'phone', 'phone2', 'address')
        ->join('companies', 'orders.id_company', '=', 'companies.id')
        ->selectRaw('companies.name AS company_name')
        ->join('order_state', 'order_state.id', '=', 'orders.status_id')
        ->selectRaw('order_state.state')
        ->get();
        session()->flash('orders', $orders);
        return view('supplies.companies.all')->with('companyname', $companyname)->with('id', $id);
    }
    public function companies_supply(Request $request)
    {
        $r = $request->except('_token', 'example1_length', 'balance', 'payed', 'name', 'id', 'total','commission','checkbox','total1');
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
            echo $value;
            $order = DB::table('orders')->where('id', '=', $value);
            if ($order->get()[0]->company_supply=='1') {
                return redirect()->back()->with('error','لقد تم التوريد من قبل');
            }
        }
        $validate = [
            'payed' => ['required']
        ];
        $request->validate($validate);
        DB::table('account_stament_companies')->insert([
            'company_id' => $request['id'],
            'payed' => $request['payed'],
            'total' => $request['total'],
            'late' => $request['total'] - $request['payed']
        ]);
        $id = DB::table('account_stament_companies')->select('id')->where('company_id', '=', $request['id'])
            ->latest('created_at')->get()[0]->id;
        $filename = "تقفيل_" . $request['name'] . "_" . $id;
        $filepath = 'companies/'.$request['name'] . '/' . str($id) . str($filename) . ".xlsx";
        Excel::store(new companies_supplies, $filepath,'public');
        DB::table('account_stament_companies')->select('*')->where('id', '=', $id)->update([
            'excel' => $filepath
        ]);
        date_default_timezone_set('Africa/Cairo');
        $path = storage_path('app/public/' . $filepath);
        if (Storage::exists($path)) {
            return Storage::download($path,'تقفيل_' . $request['name'] . '_' .'.xlsx');
        }
        // Excel::download(new companies_supplies, 'تقفيل_' . $request['name'] . '_' .'.xlsx');
        $r = $request->except('_token', 'example1_length', 'payed', 'name', 'id', 'total', 'total1');
        $profit = 0;
        $payedold = DB::table('account_stament_companies')->where('id', '=', $id)->select('payed')->get()[0]->payed;
        foreach ($checkboxValues as $r1 => $value) {
            $p1 = DB::table('orders')->where('orders.id', '=', $value)->select('orders.delegate_id','orders.company_commission','orders.delegate_commission')
                ->join('companies', 'companies.id', '=', 'orders.id_company')->get()[0];
            $profit = + ($p1->company_commission - $p1->delegate_commission);
            DB::table('orders')->where('id', '=', $value)->update([
                'company_supply' => 1,
                'company_supply_date' => now(),
                'company_commission'=>$request['commission_'.$id]
            ]);
        }
        $payed = 0 + $payedold;
        $latest0 = 0 + DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
        DB::table('keep_money')->insert([
            'moneyold' => $latest0,
            'moneyafter' => $latest0 - $payed,
            'user_id' => Auth::user()->id
        ]);
        $payedold = $request['payed'];
        $payed = 0 + $payedold;
        $latest = 0 + DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
        DB::table('profits')->insert([
            'moneyold' => $latest,
            'moneyafter' => $latest0 - $payed
        ]);
        $companies = DB::table('companies')->select('id', 'name')->get();
        $dues = array();
        $payed = array();
        foreach ($companies as $company) {
            $due = DB::table('account_stament_companies')->select('late')->where('company_id', '=', $company->id)->get();
            $dues[$company->id] = 0;
            $pay = DB::table('account_stament_companies')->select('payed')->where('company_id', '=', $company->id)->get();
            $payed[$company->id] = 0;
            $orderscost[$company->id] = 0;
            foreach ($due as $key) {
                $dues[$company->id] += $key->late;
            }
            foreach ($pay as $key) {
                $payed[$company->id] += $key->payed;
            }
        }
        return view('supplies.companies.add_d')->with('companies', $companies)->with('success', 'saved successfully')->with('dues', $dues)->with('payed', $payed);
    }

    public function h_csupplies($id)
    {
        $histories = DB::table('account_stament_companies AS dd')->select('dd.id', 'dd.company_id', 'dd.payed', 'late', 'dd.total', 'dd.excel', 'dd.created_at')->where('dd.company_id', '=', $id)
            ->join('companies AS company', 'company.id', '=', 'dd.company_id')->selectRaw('company.name')->get();
        $name = DB::table('companies')->select('name')->where('id', '=', $id)->get()[0]->name;
        return view('supplies.companies.history_supllies', compact('histories'))->with('name', $name);
    }
    public function filter()
    {
        session()->flash('active','companies.filter');
        $states = DB::table('order_state')->select('*')->get();
        return view('supplies.companies.filter')->with('states',$states);
    }
    public function filter_ajax(Request $request)
    {
        $orders = DB::table('orders')
        ->where('status_id',$request->option)
        ->where('delegate_supply','1')
        ->where('company_supply','0')
        ->join('companies','orders.id_company','companies.id')
        ->select('companies.id','companies.name')
        ->distinct()
        ->get();
        return response()->json($orders);
    }
    public function sheet($id)
    {
        $path = DB::table('account_stament_companies')->where('id',$id)->select('excel')->get();
        if (count($path)>0 && Storage::disk('public')->exists($path[0]->excel)) {
            return Storage::disk('public')->download($path[0]->excel);
        }
        abort(404, 'File not found');
    }

}
