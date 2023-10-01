<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\orders;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ordersimport implements ToCollection, WithBatchInserts
{
    public function collection(Collection $rows)
    {
        $id = request()->input('company');
        (array) $centers = DB::table('centers')->select('id', 'center_name', 'id_agent')->get();
        (array) $order_states = DB::table('order_state')->select('*')->get();
        (array) $causes = DB::table('causes_return')->select('*')->get();
        (array) $subcenters = DB::table('sub_center')->select('*')->get();
        $special_intructions2 = DB::table('companies')->select('special_intructions')->where('id', '=', $id)->get();
        if (isset($special_intructions2[0]->special_intructions)) {
            $special_intructions0 = $special_intructions2[0]->special_intructions;
        } elseif (!isset($special_intructions2[0]->special_intructions)) {
            $special_intructions0 = 'none';
        }
        unset($rows[0]);
        foreach ($rows as $row) {
            if ($row[0] != null) {
            $data = [
            'id_company' => $id ? $id:auth()->user()->company_id,
            'special_intructions2' => $special_intructions0,
        ];
        $orderid = DB::table('orders')->insertGetId($data);
        DB::table('orders')
        ->where('id',$orderid)
        ->update([
            'id_police' =>$row[13]??config('app.name') . '-' . $orderid
        ]);
                $id_police=$row[13]??config('app.name') . '-' . $orderid;
                $order = DB::table('orders')->select('*')->where('id', '=', $orderid);
                $row[5] = Carbon::instance(Date::excelToDateTimeObject($row[5]))->format('y-m-d');
                if ($row[0] == null) {
                    $row[0] = 'none';
                    $order->update([
                        'name_client' => $row[0]
                    ]);
                } else {
                    $order->update([
                        'name_client' => $row[0]
                    ]);
                }
                if ($row[1] == null) {
                    $row[1] = 'none';
                    $order->update([
                        'phone' => $row[1]
                    ]);
                } else {
                    $order->update([
                        'phone' => $row[1]
                    ]);
                }
                if ($row[2] == null) {
                    $row[2] = 'none';
                    $order->update([
                        'phone2' => $row[2]
                    ]);
                } else {
                    $order->update([
                        'phone2' => $row[2]
                    ]);
                }
                if ($row[3] == null) {
                    $row[3] = 'none';
                    $order->update([
                        'address' => $row[3]
                    ]);
                } else {
                    $order->update([
                        'address' => $row[3]
                    ]);
                }
                if ($row[4] != null) {
                    $order->update([
                        'cost' => $row[4]
                    ]);
                }
                if ($row[5] == null) {
                    $row[5] = 'none';
                    $order->update([
                        'date' => $row[5]
                    ]);
                } else {
                    $order->update([
                        'date' => $row[5]
                    ]);
                }
                if ($row[6] == null) {
                    $row[6] = 'none';
                    $order->update([
                        'notes' => $row[6]
                    ]);
                } else {
                    $order->update([
                        'notes' => $row[6]
                    ]);
                }
                if ($row[7] == null) {
                    $row[7] = 'none';
                    $order->update([
                        'special_intructions' => $row[7]
                    ]);
                } else {
                    $order->update([
                        'special_intructions' => $row[7]
                    ]);
                }
                if ($row[8] == null) {
                    $row[8] = 'none';
                    $order->update([
                        'name_product' => $row[8]
                    ]);
                } else {
                    $order->update([
                        'name_product' => $row[8]
                    ]);
                }

                if ($row[9] == null) {
                    $row[9] = 'none';
                    $order->update([
                        'sender' => $row[9]
                    ]);
                } else {
                    $order->update([
                        'sender' => $row[9]
                    ]);
                }
                if ($row[10] == null) {
                    $row[10] = 'none';
                    $order->update([
                        'weghit' => $row[10]
                    ]);
                } else {
                    $order->update([
                        'weghit' => $row[10]
                    ]);
                }
                if ($row[11] == null) {
                    $row[11] = 'none';
                    $order->update([
                        'open' => $row[11]
                    ]);
                } else {
                    $order->update([
                        'open' => $row[11]
                    ]);
                }
                if ($row[12] == null) {
                    $row[12] = 'none';
                    $order->update([
                        'identy_number' => $row[12]
                    ]);
                } else {
                    $order->update([
                        'identy_number' => $row[12]
                    ]);
                }
            }
            $order->update([
                'cause_id' => '1',
                'status_id' => '10',
                'order_locate' => '1',
                'delay_id' => '1'
            ]);
            DB::table('orders_history')->insert([
                'order_id' => $orderid,
                'action' => 'add',
                'new' => json_encode(DB::table('orders')->latest('created_at')->get()[0], JSON_UNESCAPED_UNICODE),
                'user_id' => auth()->user()->id
            ]);
        }
    }
    public function batchSize(): int
    {
        return 1000;
    }
}
