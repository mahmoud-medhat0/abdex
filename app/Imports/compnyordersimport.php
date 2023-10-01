<?php

namespace App\Imports;

use App\Models\orders;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class compnyordersimport implements WithHeadingRow,ToCollection{
    public function collection(Collection $rows)
    {
        $id = Auth::user()->company_id;
        (array) $centers = DB::table('centers')->select('id','center_name','id_agent')->get();
        (array) $order_states = DB::table('order_state')->select('*')->get();
        (array) $causes = DB::table('causes_return')->select('*')->get();
        (array) $subcenters =DB::table('sub_center')->select('*')->get();
        $special_intructions2 = DB::table('companies')->select('special_intructions')->where('id','=',$id)->get();
        if(isset($special_intructions2[0]->special_intructions)){
            $special_intructions0 = $special_intructions2[0]->special_intructions;
        }elseif(!isset($special_intructions2[0]->special_intructions)){
            $special_intructions0 = 'none';
        }
        foreach ($rows as $row){
            if($row['id_police']!=null){
            DB::table('orders')->insert([
                'id_company'=> $id,
                'id_police'=>$row['id_police'],
                'special_intructions2'=>$special_intructions0
            ]);
            $idorder=DB::table('orders')->select('id')->where('id_police','=',$row['id_police'])->get()['0']->id;
            $order=DB::table('orders')->select('*')->where('id','=',$idorder);
            $UNIX_DATE = ($row['date'] - 25569) * 86400;
            $row['date']= gmdate("y-m-d", $UNIX_DATE);
            // if ($row['id_police']==null){
            //     $row['id_police']='none';
            //     $order->update([
            //     'id_police'=>$row['id_police']
            //     ]);
            // }else{
            //     $order->update([
            //         'id_police'=>$row['id_police']
            //         ]);
            // }
            if ($row['name_client']==null){
                $row['name_client']='none';
                $order->update([
                  'name_client' =>$row['name_client']
                ]);
            }else {
                $order->update([
                    'name_client' =>$row['name_client']
                  ]);            }
            if ($row['phone']==null){
                $row['phone']='none';
                $order->update([
                    'phone' =>$row['phone']
                  ]);
            }else{
                $order->update([
                    'phone' =>$row['phone']
                  ]);
            }
            if ($row['phone2']==null){
                $row['phone2']='none';
                $order->update([
                    'phone2' =>$row['phone2']
                  ]);
            }else {
                $order->update([
                    'phone2' =>$row['phone2']
                  ]);
            }
            foreach ($centers as $center) {
                if (str_contains($row['address'],$center->center_name)){
                    $agent_id=$center->id_agent;
                    $order->update([
                        'center_id'=>$center->id,
                        // 'agent_id'=>$agent_id,
                    ]);
                }
            }
            foreach ($subcenters as $subcenter) {
                if(str_contains($row['address'],$subcenter->name)){
                    $order->update([
                        'center_id'=>$subcenter->id_center,
                    ]);
                }
            }
            if ($row['address']==null){
                $row['address']='none';
                $order->update([
                    'address'=>$row['address']
                ]);
            }else {
                $order->update([
                    'address'=>$row['address']
                ]);
            }
            if ($row['cost']!=null){
                $order->update([
                    'cost'=>$row['cost']
                ]);
            }

            if ($row['date']==null){
                $row['date']='none';
                $order->update([
                    'date'=>$row['date']
                ]);
            }else {
                $order->update([
                    'date'=>$row['date']
                ]);
            }
            if ($row['notes']==null){
                $row['notes']='none';
                $order->update([
                    'notes'=>$row['notes']
                ]);
            }else {
                $order->update([
                    'notes'=>$row['notes']
                ]);
            }

            if ($row['special_intructions']==null){
                $row['special_intructions']='none';
                $order->update([
                    'special_intructions'=>$row['special_intructions']
                ]);
            }else {
                $order->update([
                    'special_intructions'=>$row['special_intructions']
                ]);
            }
            if ($row['name_product']==null){
                $row['name_product']='none';
                $order->update([
                    'name_product'=>$row['name_product']
                ]);
            }else {
                $order->update([
                    'name_product'=>$row['name_product']
                ]);
            }

            if ($row['sender']==null){
                $row['sender']='none';
                $order->update([
                  'sender'=>$row['sender']
                ]);
            }else{
                $order->update([
                    'sender'=>$row['sender']
                  ]);
            }
            if ($row['weghit']==null){
                $row['weghit']='none';
                $order->update([
                  'weghit'=>$row['weghit']
                ]);
            }else{
                $order->update([
                    'weghit'=>$row['weghit']
                  ]);
            }
            // foreach ($premissions as $premission){
            //     if(str_contains($row['premission_id'],$premission->info)){
            //         $row['premission_id'] = $premission->id;
            //     }
            // }
            // foreach ($order_states as $order_state){
            //     if(str_contains($row['status_id'],$order_state->state)){
            //         $row['status_id'] = $order_state->id;
            //     }
            // }
            // foreach ($causes as $cause){
            //     if(str_contains($row['cause_id'],$cause->cause)){
            //         $row['cause_id'] = $cause->id;
            //     }
            // }
            // if ($row['identy_number']==null){
            //     $row['identy_number']='none';
            // }
            // dd($row);
            }
            $order->update([
                'cause_id'=>'1',
                'status_id'=>'10'
            ]);
            DB::table('orders_history')->insert([
                'order_id'=>$idorder,
                'action'=>'add',
                'new'=>json_encode(DB::table('orders')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
                'user_id'=>auth()->user()->id
            ]);
        }
    }
}
