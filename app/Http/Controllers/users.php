<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\userstoe;
use App\Http\Requests\userupdate;
use App\Models\User;
use App\Rules\validagent;
use App\Rules\validcompany;
use App\Rules\validdelegate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

class users extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth', 'admin']);
    }
    public function delegates()
    {
        session()->flash('active', 'delegates');
        $users = DB::table('uid')->select('*')->join('users', 'users.id', '=', 'uid.user_id')->selectRaw('users.name')->get();
        return view('users.delegates')->with('users', $users);
    }
    public function delegates_store(Request $request)
    {
        $this->database = \App\Services\FirebaseService::connect();
        if ($request->method == '0') {
            $data = $request->except('_token', 'method');
            foreach ($data as $key => $value) {
                $uid = DB::table('uid')->select('*')->where('user_id', '=', $value);
                $uid->update([
                    'active' => '0'
                ]);
                // $this->database
                // ->getReference('login_state/' . $uid->get()[0]->uid)
                // ->set([
                //     'active' =>'0',
                // ]);
            }
        }
        if ($request->method == '1') {
            $data = $request->except('_token', 'method');
            foreach ($data as $key => $value) {
                $uid = DB::table('uid')->select('*')->where('user_id', '=', $value);
                $uid->update([
                    'active' => '1'
                ]);
                // $this->database
                // ->getReference('login_state/' . $uid->get()[0]->uid)
                // ->set([
                //     'active' =>'1',
                // ]);
            }
        }
        return redirect()->back()->with('success', 'تم التحديث بنجاح');
    }
    public function firebase_read()
    {
        session()->flash('active', 'firebase_read');
        $this->database = \App\Services\FirebaseService::connect();
        $users = $this->database->getReference('login_state/')->getValue();
        return view('users.firebase')->with('users', $users);
    }
    public function firebase_store(Request $request)
    {
        $this->database = \App\Services\FirebaseService::connect();
        if ($request->method == '0') {
            $data = $request->except('_token', 'method');
            foreach ($data as $key => $value) {
                $this->database
                    ->getReference('login_state/' . $value)
                    ->update([
                        'active' => '0',
                    ]);
            }
        }
        if ($request->method == '1') {
            $data = $request->except('_token', 'method');
            foreach ($data as $key => $value) {
                $this->database
                    ->getReference('login_state/' . $value)
                    ->update([
                        'active' => '1',
                    ]);
            }
        }
        return redirect()->back()->with('success', 'تم التحديث بنجاح');
    }
    public function delegates_delete($id)
    {
        $userid = DB::table('uid')->where('user_id','=',$id)->select('user_id','uid')->get()[0];
        DB::table('uid')->where('user_id','=',$id)->delete();
        DB::table('personal_access_tokens')->where('tokenable_id','=',$userid->user_id)->delete();
        $this->database = \App\Services\FirebaseService::connect();
        $this->database->getReference('login_state/' . $userid->uid)->remove();
        $this->database->getReference('location/' . $userid->uid)->remove();
        $this->database->getReference('active/' . $userid->uid)->remove();
        return redirect()->back()->with('success', 'تم حذف الجهاز بنجاح');
    }
    public function add()
    {
        session()->flash('active', 'user_add');
        $ranks = DB::table('ranks')->select('*')->get();
        $companies = DB::table('companies')->select('id', 'name')->get();
        return view('users.add', compact('ranks'))->with('companies', $companies);
    }
    public function store(userstoe $REQUEST)
    {
        $validate = [
            'name' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:users,username'],
            'password' => ['required', 'min:8', 'confirmed'],
            'rank' => ['required', 'in:1,2,3,4,5,6,7,8,9'],
            'gender' => ['required', 'in:m,f'],
        ];
        switch ($REQUEST['rank']) {
            case '7':
                $validate['company_id'] = ['required', new validcompany, 'unique:users,company_id'];
                break;
                // case '9':
                //     $validate['identy']= [File::types(['pdf'])];
                //     $validate['drvlicence']= ['required',File::types(['pdf'])];
                //     $validate['bilkelicense']= ['required',File::types(['pdf'])];
                //     $validate['phonep']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phonep'];
                //     $validate['phonew']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phonew'];
                //     $validate['phone3']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phone3'];
                //     $REQUEST->validate($validate);
                //     break;
                // case '8':
                //     $validate['phonep']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phonep'];
                //     $validate['phonew']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phonew'];
                //     $validate['phone3']=['regex:/^01[0-2,5]\d{8}$/','unique:users,phone3'];
                //     $validate['commision']=['int'];
                //     $REQUEST->validate($validate);
                //     break;
                // default:
                //     $validate['identy']= [File::types(['pdf'])];
                //     $REQUEST->validate($validate);
                //     break;
        }
        $REQUEST->validate($validate);
        if ($REQUEST['address'] == null) {
            $address = '*';
        } else {
            $address = $REQUEST['address'];
        }
        if ($REQUEST['commision'] == null) {
            $commision = 0;
        } else {
            $commision = $REQUEST['commision'];
        }
        if ($REQUEST['phonep'] != null) {
            $phonep = $REQUEST['phonep'];
        } else {
            $phonep = '*';
        }
        if ($REQUEST['phonew'] != null) {
            $phonew = $REQUEST['phonew'];
        } else {
            $phonew = '*';
        }
        if ($REQUEST['phone3'] != null) {
            $phone3 = $REQUEST['phone3'];
        } else {
            $phone3 = '*';
        }
        DB::table('users')->insert([
            'name' => $REQUEST['name'],
            'username' => $REQUEST['username'],
            'password' => Hash::make($REQUEST['password']),
            'gender' => $REQUEST['gender'],
            'address' => $address,
            'identy_number' => '*',
            'Driving_License' => '*',
            'bike_license' => '*',
            'rank_id' => $REQUEST['rank'],
            'phonep' => $phonep,
            'phonew' => $phonew,
            'phone3' => $phone3,
            'commision' => $commision
        ]);
        $id = DB::table('users')->select('id')->where('username', '=', $REQUEST['username'])->get()['0']->id;
        if (isset($REQUEST['identy'])) {
            $pathidenty = $REQUEST->file('identy')->storeAs($id, 'identy.pdf', 'public');
            $pathidenty = "/storage/" . $pathidenty;
        }
        if (isset($REQUEST['drvlicence'])) {
            $pathdrvlicence = $REQUEST->file('drvlicence')->storeAs($id, 'drvlicence.pdf', 'public');
            $pathdrvlicence = "storage/" . $pathdrvlicence;
        }
        if (isset($REQUEST['bilkelicense'])) {
            $pathbilkelicense = $REQUEST->file('bilkelicense')->storeAs($id, 'bilkelicense.pdf', 'public');
            $pathbilkelicense = "/storage/" . $pathbilkelicense;
        }
        if ($REQUEST['identy'] == null) {
            $pathidenty = '*';
        }
        if ($REQUEST['drvlicence'] == null) {
            $pathdrvlicence = '*';
        }
        if ($REQUEST['bilkelicense'] == null) {
            $pathbilkelicense = '*';
        }

        DB::table('users')->select('*')->where('id', '=', $id)->update([
            'identy_number' => $pathidenty,
            'Driving_License' => $pathdrvlicence,
            'bike_license' => $pathbilkelicense,
        ]);
        switch ($REQUEST['rank']) {
            case '7':
                DB::table('users')->where('id', '=', $id)->update([
                    'company_id' => request('company_id')
                ]);
                break;
            case '9':
                break;
            case '8':
                break;
            default:
                DB::table('salaries')->insert([
                    'id_user' => $id,
                    'salary' => $commision
                ]);
                break;
        }
        $user = DB::table('users')->select('*')->where('id', '=', $id)->get();
        DB::table('users_history')->insert([
            'action' => 'add',
            'new' => json_encode($user[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->route('users')->with('success', 'user addedd successfully');
    }
    public function read()
    {
        session()->flash('active', 'users');
        $users = User::all();
        return view('users.allusers', compact('users'));
    }
    public function edit($id)
    {
        $ranks = DB::table('ranks')->select('*')->get();
        $user = DB::table('users')->select('*')->where('id', '=', $id)->get();
        $companies = DB::table('companies')->select('id', 'name')->get();
        $user = $user['0'];
        return view('users.edit')->with('ranks', $ranks)->with('user', $user)->with('companies', $companies);
    }
    public function update(userupdate $req)
    {
        $id = $req['id'];
        $datauserold = DB::table('users')->where('id', '=', $id)->select('*')->get();
        $user = DB::table('users')->where('id', '=', $id);
        $userdata = $user->get()['0'];
        $userold = $userdata->username;
        $validate = array();
        if ($req['username'] != $userold) {
            $validateuser = ['username' => ['required', 'max:255', 'unique:users,username']];
            $req->validate($validateuser);
            $user->update([
                'username' => $req['username'],
            ]);
        } else {
            $validate['username'] = ['required', 'max:255'];
            $user->update([
                'username' => $req['username'],
            ]);
        }
        $validate = [
            'name' => ['required', 'max:255'],
            'rank' => ['required', 'in:1,2,3,4,5,6,7,8,9'],
            'gender' => ['required', 'in:m,f'],
        ];
        $req->validate($validate);
        if (isset($req['identy'])) {
            $validateid = ['identy' => ['required', File::types(['pdf'])]];
            $req->validate($validateid);
            Storage::delete('/storage/app/public/' . $id . '/identy.pdf');
            $pathidenty = $req->file('identy')->storeAs($id, 'identy.pdf', 'public');
            $pathidenty = "/storage/" . $pathidenty;
            $user->update([
                'identy_number' => $pathidenty
            ]);
        }
        if (isset($req['drvlicence'])) {
            $validatedrv = ['drvlicence' => ['required', File::types(['pdf'])]];
            $req->validate($validatedrv);
            Storage::delete('/storage/app/public/' . $id . '/drvlicence.pdf');
            $pathdrvlicence = $req->file('drvlicence')->storeAs($id, 'drvlicence.pdf', 'public');
            $pathdrvlicence = "storage/" . $pathdrvlicence;
            $user->update([
                'Driving_License' => $pathdrvlicence
            ]);
        }
        if (isset($req['bilkelicense'])) {
            $validatebike = ['bilkelicense' => ['required', File::types(['pdf'])]];
            $req->validate($validatebike);
            Storage::delete('/storage/app/public/' . $id . '/bilkelicense.pdf');
            $pathbilkelicense = $req->file('bilkelicense')->storeAs($id, 'bilkelicense.pdf', 'public');
            $pathbilkelicense = "/storage/" . $pathbilkelicense;
            $user->update([
                'bike_license' => $pathbilkelicense
            ]);
        }
        if (isset($req['address'])) {
            $address = $req['address'];
        }
        if ($req['address'] == null) {
            $address = '*';
        }
        if ($req['phonep'] != null) {
            $phonep = $req['phonep'];
        } elseif ($req['phonep'] == null) {
            $phonep = '*';
        }
        if ($req['phonew'] != null) {
            $phonew = $req['phonew'];
        } else {
            $phonew = '*';
        }
        if ($req['phone3'] != null) {
            $phone3 = $req['phone3'];
        } else {
            $phone3 = '*';
        }
        $user->update([
            'name' => $req['name'],
            'address' => $address,
            'rank_id' => $req['rank'],
            'gender' => $req['gender'],
            'phonep' => $phonep,
            'phonew' => $phonew,
            'phone3' => $phone3,
            'commision' => $req['commision']
        ]);
        if ($req['password'] != null) {
            $validatepass = ['password' => ['required', 'min:8', 'confirmed']];
            $req->validate($validatepass);
            $user->update([
                'password' => Hash::make($req['password']),
            ]);
        }
        switch ($req['rank']) {
            case '9':
                break;
            case '8':
                break;
            case '7':
                DB::table('users')->where('id', '=', $id)->update([
                    'company_id' => request('company_id')
                ]);
                break;

            default:
                DB::table('salaries')->where('id_user', '=', $id)->update([
                    'salary' => $req['commision']
                ]);
                break;
        }
        $user = DB::table('users')->select('*')->where('id', '=', $id)->get();
        DB::table('users_history')->insert([
            'action' => 'edit',
            'old' => json_encode($datauserold[0], JSON_UNESCAPED_UNICODE),
            'new' => json_encode($user[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        return redirect()->route('users')->with('success', 'user updated successfully');
    }
    public function delete($id)
    {
        $user = User::where('id', '=', $id);
        Storage::delete('/storage/app/public/' . $id . '/identy.pdf');
        Storage::delete('/storage/app/public/' . $id . '/drvlicence.pdf');
        Storage::delete('/storage/app/public/' . $id . '/bilkelicense.pdf');
        DB::table('users_history')->insert([
            'action' => 'delete',
            'old' => json_encode(DB::table('users')->select('*')->where('id', '=', $id)->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        DB::table('salaries')->select('*')->where('id_user', $id)->delete();
        $user->delete();
        return redirect()->back()->with('success', 'user deleted successfull');
    }
    public function manage_index()
    {
        session()->flash('active', 'manage_index');
        $persons = DB::table('agents_delegates')->join('users', 'agents_delegates.agent_id', '=', 'users.id')
            ->join('users AS delegate', 'delegate.id', '=', 'agents_delegates.delegate_id')->selectRaw('agents_delegates.id')->selectRaw('users.name AS agentname')
            ->selectRaw('delegate.name')->selectRaw('agents_delegates.created_at')->get();
        return view('agents_delegates.all')->with('persons', $persons);
    }
    public function manage_add()
    {
        $agents = DB::table('users')->where('rank_id', '=', '8')->select('*')->get();
        $delegates = DB::table('users')->where('rank_id', '=', '9')->select('*')->get();
        return view('agents_delegates.add')->with('agents', $agents)->with('delegates', $delegates);
    }
    public function manage_store()
    {
        $validate = [
            'agent' => ['required', new validagent],
            'delegate' => ['required', new validdelegate, 'unique:agents_delegates,delegate_id']
        ];
        request()->validate($validate);
        DB::table('agents_delegates')->insert([
            'agent_id' => request()->agent,
            'delegate_id' => request()->delegate_id
        ]);
        return redirect()->back()->with('success', 'تم الربط بنجاح');
    }
    public function manage_edit($id)
    {
        $agents = DB::table('users')->where('rank_id', '=', '8')->select('*')->get();
        $delegates = DB::table('users')->where('rank_id', '=', '9')->select('*')->get();
        $record = DB::table('agents_delegates')->where('id', $id)->selectRaw('agents_delegates.agent_id')
            ->selectRaw('agents_delegates.delegate_id')->get();
        session()->flash('id', $id);
        return view('agents_delegates.edit')->with('agents', $agents)->with('delegates', $delegates)->with('record', $record);
    }
    public function manage_update()
    {
        $id = session()->get('id');
        $validate = [
            'agent' => ['required', new validagent],
            'delegate' => ['required', new validdelegate, 'unique:agents_delegates,delegate_id|unique:agents_delegates,delegate_id']
        ];
        request()->validate($validate);
        DB::table('agents_delegates')->update([
            'agent_id' => request()->agent,
            'delegate_id' => request()->delegate
        ]);
        session()->forget('id');
        return redirect()->back()->with('success', 'تم التحديث بنجاح');
    }
    public function manage_delete($id)
    {
        DB::table('agents_delegates')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
    public function track()
    {
        session()->flash('active','trackdelegates');
        return view('users.map');
    }
}
