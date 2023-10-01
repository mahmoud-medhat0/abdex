<?php

namespace App\Http\Controllers;

use App\Rules\validcommision;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class companies extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function read()
    {
        session()->flash('active','companies');
        $companies = DB::table('companies')->select('*')->get();
        $company1 = array();
        foreach ($companies as $company){
            $company =(array) $company;
            (array) $numbers = DB::table('companies_phones')->select('*')->where('id_companiy','=',$company['id'])->get();
            $company['num'] = array();
            array_push($company['num'],$numbers);
            array_push($company1,$company);
        }
        return view('companies.all',compact('company1'));
    }
    public function add()
    {
        session()->flash('active','companies_add');
        return view('companies.add');
    }
    public function store(Request $request)
    {
        $validate = [
            'name' => ['required','max:255'],
            'special_intructions'=>['nullable']
        ];
        $request->validate($validate);
        DB::table('companies')->insert([
            'name' => $request['name'],
            'special_intructions'=>$request['special_intructions']? $request['special_intructions']:'none'
        ]);
        DB::table('companies_history')->insert([
            'action'=>'add',
            'new'=>json_encode(DB::table('companies')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->route('companies')->with('success','company addedd successfully');
    }
    public function addnum()
    {
        session()->flash('active','company_addnum');
        $companies = DB::table('companies')->select('*')->get();
        return view('companies.addnumber',compact('companies'));
    }
    public function num_store(Request $request)
    {
        $validate = [
            'company' =>['required','exists:companies,id'],
            'note' => ['required','max:255'],
            'notear'=> ['required','max:255'],
            'number' => ['required','numeric','min:10']
        ];
        $request->validate($validate);
        DB::table('companies_phones')->insert([
            'note_en'=>$request['note'],
            'note_ar'=>$request['notear'],
            'phone_number'=>$request['number'],
            'id_companiy'=>$request['company']
        ]);
        DB::table('companies_phones_history')->insert([
            'action'=>'add',
            'new'=>json_encode(DB::table('companies_phones')->latest('created_at')->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $companies = DB::table('companies')->select('*')->get();
        return redirect()->back()->with('success','phone added to company successfully')->with('companies',$companies);
    }
    public function edit($id)
    {
        $company = DB::table('companies')->select('*')->where('id','=',$id)->get()['0'];
        $numbers = DB::table('companies_phones')->select('*')->where('id_companiy','=',$id)->get();
        return view('companies.edit')->with('company',$company)->with('numbers',$numbers);
    }
    public function update(Request $request)
    {
        $data = $request->except('_token','_method');
        $company = DB::table('companies')->select('*')->where('id','=',$data['id']);
        $companyold= $company->get();
        $company->update([
            'name' => $data['name'],
            'special_intructions'=>$data['special_intructions']
        ]);
        DB::table('companies_history')->insert([
            'action'=>'edit',
            'old'=>json_encode($companyold,JSON_UNESCAPED_UNICODE),
            'new'=>json_encode($company->get(),JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $data1 =$request->except('_token','_method','id','name');
        $data1 = array_keys($data1);
        $notesar = array();
        $notesen = array();
        $phones = array();
        $ids = array();
        $validate=array();
        $validate['special_intructions']='required|max:255';
        foreach($data1 as $rec){
            $a = explode("_", $rec);
            if($a[0] =='notear'){
                array_push($notesar,$rec);
            }
            if($a[0]=='noteen'){
                array_push($notesen,$rec);
            }
            if ($a[0] =='number'){
                array_push($phones,$rec);
            }
            if(!in_array($a[1],$ids)){
                array_push($ids,$a[1]);
            }
        }
        foreach ($notesar as $notear) {
            $validate[$notear] = 'required|max:255';
        }
        foreach ($notesen as $noteen) {
            $validate[$noteen] = 'required|max:255';
        }
        foreach ($phones as $phone) {
            $validate[$phone] = 'required|numeric|min:10';
        }
        $request->validate($validate);
        foreach ($ids as $id) {
            $update = DB::table('companies_phones')->select('*')->where('id','=',$id);
            if(isset($request['noteen_'.$id])){
                $update->update([
                    'note_en' => $request['noteen_'.$id],
                ]);
            }
            if(isset($request['notear_'.$id])){
                $update->update([
                    'note_ar' => $request['notear_'.$id],
                ]);
            }
            if(isset($request['number_'.$id])){
                $updateold = $update->get();
                $update->update([
                    'phone_number' => $request['number_'.$id]
                ]);
                DB::table('companies_phones_history')->insert([
                    'action'=>'edit',
                    'old'=>json_encode($updateold[0],JSON_UNESCAPED_UNICODE),
                    'new'=>json_encode($update->get()[0],JSON_UNESCAPED_UNICODE),
                    'user_id'=>auth()->user()->id
                ]);
            }
                }
        $company = DB::table('companies')->select('*')->where('id','=',$id)->get();
        $numbers = DB::table('companies_phones')->select('*')->where('id_companiy','=',$id)->get();
        return redirect()->route('companies')->with('success','company data updated successfully')->with('company',$company)->with('numbers',$numbers);;
    }
    public function delete(Request $request,$id)
    {
        $phones = DB::table('companies_phones')->select('*')->where('id_companiy','=',$id);
        if(isset($phones->get()[0])){
            DB::table('companies_phones_history')->insert([
                'action'=>'edit',
                'old'=>json_encode($phones->get()[0],JSON_UNESCAPED_UNICODE),
                'user_id'=>auth()->user()->id
            ]);
            $phones->delete();
        }
        $company = DB::table('companies')->select('*')->where('id','=',$id);
        DB::table('companies_history')->insert([
            'action'=>'delete',
            'old'=>json_encode($company->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        $company->delete();
        $request->session()->forget('companies_add');
        $request->session()->put('companies','active');
        $companies = DB::table('companies')->select('*')->get();
        $company1 = array();
        foreach ($companies as $company){
            $company =(array) $company;
            (array) $numbers = DB::table('companies_phones')->select('*')->where('id_companiy','=',$company['id'])->get();
            $company['num'] = array();
            array_push($company['num'],$numbers);
            array_push($company1,$company);
        }
        return redirect()->back()->with('success','company data deleted successfully')->with('company1',$company1);

    }
}
