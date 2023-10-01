<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\valididpersonfin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class financiace_accountants extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function add(Request $request)
    {
        session()->flash('active','add_finance');
        $persons = DB::table('persons_accounts_financial')->select('id','name')->get();
        return view('financial.accountants.add')->with('persons',$persons);
    }

    public function store(Request $request)
    {
        $validate = [
            'person'=>['required',new valididpersonfin],
            'creditor'=>['required','int'],
            'debtor'=>['required','int'],
            'cause'=>['required']
        ];
        $request->validate($validate);
        DB::table('accounts_financial')->insert([
            'person_id'=>$request['person'],
            'creditor'=>$request['creditor'],
            'debtor'=>$request['debtor'],
            'cause'=>$request['cause'],
            'confirm'=>'1',
            'user_id'=>Auth::user()->id
        ]);
        $total = $request['debtor']-$request['creditor'];
        $latest = DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
        DB::table('keep_money')->insert([
            'moneyold'=>$latest,
            'moneyafter'=>$latest+$total,
            'user_id'=>Auth::user()->id
        ]);
        return redirect()->back()->with('success','تمت الاضافه بنجاح');
    }
    public function person_acontant($id)
    {
        $name= DB::table('persons_accounts_financial')->select('name')->where('id','=',$id)->get()[0]->name;
        $accountants=DB::table('accounts_financial')->select('*')->where('person_id','=',$id)->get();
        return view('financial.accountants.history')->with('accountants',$accountants)->with('name',$name);
    }
    public function accountants_hold()
    {
        session()->flash('active','accountants_hold');
        $accountants = DB::table('accounts_financial')->where('confirm','0')->select('accounts_financial.id','accounts_financial.creditor','accounts_financial.debtor','accounts_financial.cause','accounts_financial.confirm','accounts_financial.created_at')->join('users','users.id','=','accounts_financial.user_id')->selectRaw('users.name')
        ->join('persons_accounts_financial','accounts_financial.person_id','persons_accounts_financial.id')->selectRaw('persons_accounts_financial.name As person_name')
        ->get();
        return view('financial.accountants.hold')->with('accountants',$accountants);
    }
    public function accountants_hold_store(Request $request)
    {
        $checks = $request->except('_token');
        foreach ($checks as $r1 => $value) {
            DB::table('accounts_financial')->where('id', '=', $value)->update([
                'confirm' => 1,
            ]);
        }
        return redirect()->back()->with('success','تم التاكيد بنجاح');
    }
}
