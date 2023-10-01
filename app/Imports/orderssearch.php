<?php

namespace App\Imports;

use App\Models\orders;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class orderssearch implements ToCollection{
    public function collection(Collection $rows)
    {
        $objectA = array();
        $missing = array();
        foreach ($rows as $row => $value){
            if ($value[0]!=null) {
                $objectB=DB::table('orders')->select('*')->where('id_police','=',$value[0])->get();
                if($objectB==null|$objectB=='[]'){
                    array_push($missing,$value[0].' is missing');
                }else{
                    $obj_merged[$row] = $objectB[0];
                }
                }
            }
            // dd($obj_merged);
            if (isset($obj_merged)) {
                session()->flash('orders',$obj_merged);
            }
            session()->flash('missing',$missing);
            }
    }

