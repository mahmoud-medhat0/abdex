<?php
  
namespace App\Imports;
  
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
  
class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        (array) $premissions = DB::table('premissions_order')->select('*')->get();
        foreach ($premissions as $premission){
            if(str_contains($row['premission'],$premission['info'])){
                $row['premission'] = $premission['id'];
            }
            else{
                $row['premission'] = 'undifened';
            }
        }
        return new User([
            'name'     => $row['name'],
            'username'    => $row['username'], 
            'password' => Hash::make($row['password']),
        ]);
    }
}