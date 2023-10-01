<?php

namespace App\Http\Controllers;

use App\Rules\valididpersonfin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class persons_accounts_financial extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }

    public function add()
    {
        session()->flash('active','add_personfin');
        return view('financial.persons.add');
    }

    public function store(Request $request)
    {
        $validate = [
            'name'=>['required'],
            'rank'=>['required']
        ];
        $request->validate($validate);
        DB::table('persons_accounts_financial')->insert([
            'name'=>$request['name'],
            'rank'=>$request['rank']
        ]);
        DB::table('persons_accounts_financial_history')->insert([
            'action'=>'add',
            'new'=>json_encode(DB::table('persons_accounts_financial')->latest('created_at')->get(),JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','تمت اضافه الشخص بنجاح الى كشف الحساب');
    }

    public function index()
    {
        session()->flash('active','pesronsfin');
        $persons = DB::table('persons_accounts_financial')->select('*')->get();
        $dues= array();
        $payed= array();
        foreach ($persons as $person) {
            $due = DB::table('accounts_financial')->select('creditor')->where('person_id','=',$person->id)->get();
            $dues[$person->id] = 0;
            $pay = DB::table('accounts_financial')->select('debtor')->where('person_id','=',$person->id)->get();
            $payed[$person->id] = 0;
            $orderscost[$person->id] =0;
            foreach ($due as $key) {
                $dues[$person->id]+=$key->creditor;
            }
            foreach ($pay as $key) {
                $payed[$person->id]+=$key->debtor;
            }
        }
        return view('financial.persons.all')->with('persons',$persons)->with('dues',$dues)->with('payed',$payed);
    }

    public function edit($id)
    {
        $person = DB::table('persons_accounts_financial')->select('*')->where('id','=',$id)->get()[0];
        return view('financial.persons.edit')->with('person',$person)->with('id',$id);
    }

    public function update(Request $request)
    {
        $validate = [
            'name'=>['required'],
            'rank'=>['required'],
            'id'=>['required',new valididpersonfin]
        ];
        $request->validate($validate);
        $person=DB::table('persons_accounts_financial')->where('id','=',$request['id']);
        $personold=$person->get();
        $person->update([
            'name'=>$request['name'],
            'rank'=>$request['rank']
        ]);
        DB::table('persons_accounts_financial_history')->insert([
            'action'=>'add',
            'old'=>json_encode($personold[0],JSON_UNESCAPED_UNICODE),
            'new'=>json_encode($person->get()[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','تم تحديث بيانات الشخص بنجاح');
    }

    public function delete($id)
    {
        $person = DB::table('persons_accounts_financial')->select('*')->where('id','=',$id);
        $personold= $person->get();
        $person->delete();
        DB::table('persons_accounts_financial_history')->insert([
            'action'=>'add',
            'old'=>json_encode($personold[0],JSON_UNESCAPED_UNICODE),
            'user_id'=>auth()->user()->id
        ]);
        return redirect()->back()->with('success','تم حذف الشخص بنجاح');
    }
}
