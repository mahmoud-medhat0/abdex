<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuppliesExport implements FromView, ShouldAutoSize
{
    public $orders;

    public function view(): View
    {
        $id = request()->id;
        $payed = request()->payed;
        $commission = request()->commission;
        $commissions = [];
        foreach (request()->all() as $inputName => $inputValue) {
            if (strpos($inputName, 'commission_') === 0) {
                $orderid = str_replace('commission_', '', $inputName);
                $commissionName = 'commission_' . $orderid;
                $commissionValue = request()->input($commissionName);
                $commissions[$orderid] = $commissionValue;
            }
        }
        $r = request()->except('_token', 'example1_length');
        $orders = DB::table('orders')->select('orders.address','orders.id', 'orders.id_company', 'id_police', 'name_client', 'phone', 'phone2', 'delegate_id', 'orders.delegate_supply')
            ->where('on_archieve','0')->where('delegate_id', '=', $id)
            ->where('delegate_supply','0')->where(function($q){
                $q->where('status_id', '=', '1')
                ->orWhere('status_id', '=', '2')
                ->orWhere('status_id', '=', '8')
                ->orWhere('status_id', '=', '7')
                ->orWhere('status_id', '=', '4')
                ->orWhere('status_id', '=', '5');
            })
            ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state')
            ->selectRaw('orders.address')->selectRaw('orders.cost')
            ->join('companies', 'orders.id_company', '=', 'companies.id')->selectRaw('companies.name AS company_name')
            ->get();
        $agentname = DB::table('users')->select('name')->where('id','=',$id)->get()[0]->name;
        return view('table', [
            'orders' => $orders,
            'r' => $r,
            'payed' => $payed,
            'agentname'=>$agentname,
            'commissions'=>$commissions,
            'commission'=>$commission
        ]);
    }
}
