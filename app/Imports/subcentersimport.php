<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class subcentersimport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach ($collection as $row) {
            // dd($row[0]);
            DB::table('sub_center')->insert([
                'id_center'=>request()->input('center'),
                'name'=>$row[0]
            ]);
            DB::table('sub_centers_history')->insert([
                'action'=>'add',
                'new'=>json_encode(DB::table('sub_center')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
                'user_id'=>auth()->user()->id
            ]);

        }
    }
}
