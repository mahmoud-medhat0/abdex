<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Imports\ordersimport;
use App\Rules\ValidOrderLogic;
use App\Rules\ValidOrderLocate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Imports\compnyordersimport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\File;


class iscompanycontroller extends Controller
{
    public function __construct()
    {
        session()->flash('parent', 'company');
        return $this->middleware(['auth', 'iscompany','RankParent']);
    }
    public function index()
    {
        session()->flash('active', 'orderscompany');
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('id_company', '=', Auth::user()->company_id)->orderBy('created_at', 'desc')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $orders);
        return view('iscompany.orders')
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate);
    }
    public function add_manual()
    {
        session()->flash('active','companyaddmanual');
        return view('iscompany.addmanual');
    }
    public function addfromsheet()
    {
        session()->flash('active', 'companyaddsheet');
        return view('iscompany.addfromsheet');
    }
    public function storesheet()
    {
        request()->validate([
            'sheet' => ['required', File::types(['xls', 'xlsx'])]
        ]);
        Excel::import(new ordersimport, request()->file('sheet'));

        return redirect()->back()->with('success', 'data imported succesfull to database');
    }
    public function store_m(Request $request)
    {
        $validate = [
            'name_client' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'cost' => ['required', 'int'],
        ];
        $request->validate($validate);
        $special_intructions2 = DB::table('companies')->select('special_intructions')->where('id', '=', Auth::user()->company_id)->get()[0];
        if(isset($special_intructions2)){
            $special_intructions2= $special_intructions2->special_intructions;
        }else{
            $special_intructions2= 'none';
        }
        $data = [
            'id_company' => Auth::user()->company_id,
            'name_client' => $request->name_client,
            'phone' => $request->phone,
            'special_intructions2' => $special_intructions2,
            'date' => now(),
            'address' => $request['address'],
            'cost' => $request['cost'],
            'order_locate'=>'0',
            'cause_id' => '1',
            'status_id' => '10',
            'delay_id'=>'1'
        ];
        $id = DB::table('orders')->insertGetId($data);
        DB::table('orders')
        ->where('id',$id)
        ->update([
            'id_police' => config('app.name') . '-' . $id,
            'name_client'=>$request->name_client ?? 'none',
            'phone'=>$request->phone ?? 'none',
            'phone2'=>$request->phone2 ?? 'none',
            'address'=>$request->address ?? 'none',
            'cost'=>$request->cost ?? '0',
            'notes'=>$request->notes ?? 'none',
            'special_intructions'=>$request->special_intructions ??'none',
            'name_product'=>$request->name_product ?? 'none',
            'sender'=>$request->sender ?? 'none',
            'weghit'=>$request->weghit ?? 'none',
            'open'=>$request->open ?? 'none',
            'identy_number'=>$request->identy_number ?? 'none'

        ]);
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'add',
            'new' => json_encode(DB::table('orders')->latest('created_at')->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => Auth::user()->id
        ]);
        $currentDate = Carbon::today()->toDateString();
        $companyname = DB::table('companies')->where('id',Auth::user()->company_id)->select('name')->get()[0]->name;
        $title = 'تمت اضافة طرود من شركة '.$companyname;
        if (count(Notification::whereDate('created_at', $currentDate)->where('title',$title)->get())>0) {
            Notification::whereDate('created_at', $currentDate)->where('title',$title)->delete();
        }
            foreach(User::whereNot('rank_id', '7')->get() as $user) {
                $notification = new Notification();
                $notification->title = $title;
                $notification->notifiable_id = $user->id;
                $notification->data = $title;
                $notification->notifiable_type = 'user';
                $notification->save();
            }
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'encrypted' => true,
            ];
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
            $notification1 = new Notification();
            $notification1->title = $title;
            $notification1->notifiable_id = $user->id;
            $notification1->data = $title;
            $notification1->notifiable_type = 'user';
            $notification1->save();
            $notification1->created_at->format('Y-m-d H:i:s');
            $notification1->created_at_date = $notification1->created_at->format('Y-m-d');
            $notification1->created_at_time = $notification1->created_at->format('h:i:s A');
            $pusher->trigger('notifications', 'OrderUpdate', $notification1);
        return redirect()->back()->with('success', 'تمت اضافه الطرد بنجاح');
    }
    public function edit($id)
    {
        $order = DB::table('orders')->where('id',$id)->select('*')->get()[0];
        return view('iscompany.edit')->with('order',$order);
    }
    public function update(Request $request,$id)
    {
        $validate = [
            'name_client' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'cost' => ['required', 'int'],
        ];
        $request->validate($validate);
        DB::table('orders')->where('id',$id)->update([
            'name_client'=>$request->name_client ?? 'none',
            'phone'=>$request->phone ?? 'none',
            'phone2'=>$request->phone2 ?? 'none',
            'address'=>$request->address ?? 'none',
            'cost'=>$request->cost ?? '0',
            'notes'=>$request->notes ?? 'none',
            'special_intructions'=>$request->special_intructions ??'none',
            'name_product'=>$request->name_product ?? 'none',
            'sender'=>$request->sender ?? 'none',
            'weghit'=>$request->weghit ?? 'none',
            'open'=>$request->open ?? 'none',
            'identy_number'=>$request->identy_number ?? 'none'
        ]);
        return redirect()->back()->with('success', 'تمت تحديث الطرد بنجاح');
    }
    public function history()
    {
        session()->flash('parent', 'company');
        session()->flash('active', 'companyhistory');
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('id_company', '=', Auth::user()->company_id)->select('*')->get();
        $data = array();
        $ids = array();
        foreach ($orders as $order) {
            $record = DB::table('orders_history')->select('*')->where('order_id', '=', $order->id)
                ->where('action', '=', 'update by delegate')->orWhere('action', '=', 'update')
                ->join('users', 'users.id', '=', 'orders_history.user_id')->selectRaw('users.name')
                ->selectRaw('orders_history.id AS sid')
                ->get();
            array_push($data, $record);
        }
        // dd($data);
        $oldvalues = array();
        $newvalues = array();
        if ($data == '[]') {
            foreach ($data[0] as $order => $value) {
                $old = json_decode($value->old);
                $new = json_decode($value->new);
                $oldvalues[$value->sid] = array();
                $newvalues[$value->sid] = array();
                foreach ($old as $old1 => $value1) {
                    // dd($old1,$value1);
                    if ($old->$old1 != $new->$old1) {
                        $oldvalues[$value->sid][$old1] = array();
                        // $oldvalues[$value->sid][$old1][$old->$old1] = array();
                        $newvalues[$value->sid][$old1] = array();
                        // $newvalues[$value->sid][$old1][$new->$old1] = array();
                        array_push($oldvalues[$value->sid][$old1], $old->$old1);
                        array_push($newvalues[$value->sid][$old1], $new->$old1);
                    }
                }
            }
        }
        return view('history.orders')->with('data', $data)->with('oldvalues', $oldvalues)->with('newvalues', $newvalues);
    }
    public function archieve()
    {
        session()->flash('parent', 'company');
        $orders = DB::table('orders')->where('on_archieve', '=', '0')->where('id_company', '=', Auth::user()->company_id)->select('*')->get();
        $data = array();
        $ids = array();
        foreach ($orders as $order) {
            $record = DB::table('orders_history')->select('*')->where('id', '=', $order->id)->where('action', '=', 'update by delegate')->orWhere('action', '=', 'update')
                ->join('users', 'users.id', '=', 'orders_history.user_id')->selectRaw('users.name')
                ->selectRaw('orders_history.id AS sid')->get();
            array_push($data, $record);
        }
        // session()->flash('active', 'history_arcieve');
        // $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '1')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($data as $order) {
            if (in_array($order['id_police'], $polices)) {
                array_push($police_duplicate, $order['id_police']);
            } else {
                array_push($polices, $order['id_police']);
            }
        }
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        session()->flash('orders', $data);
        return view('history.archieve')
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate);
    }
    public function checks(Request $request)
    {
        $method = $request['method'];
        switch ($method) {
            case 'destroy':
                $request->validate(['value' => ['required', 'in:1,2']]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox');
                switch ($request['value']) {
                    case '1':
                        $errors =[];
                        foreach ($checks as $key => $value) {
                            $order = DB::table('orders')->where('id', '=', $value)->select('*');
                            if ($order->get()[0]->order_locate == '0') {
                                $orderold = $order->get();
                                DB::table('orders_history')->insert([
                                    'order_id' => $value,
                                    'action' => 'delete',
                                    'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                                    'user_id' => auth()->user()->id
                                ]);
                                $order->delete();
                                }else {
                                    $orderold = $order->get();
                                    DB::table('orders_history')->insert([
                                        'order_id' => $value,
                                        'action' => 'try delete',
                                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                                        'user_id' => auth()->user()->id
                                    ]);
                                    $errors[$value] = 'لا يمكن حذف طرد '.$value;
                                }
                        }
                        $errors = new MessageBag($errors);
                        if (count($errors)>0) {
                            return redirect()->back()->withErrors($errors);
                        }
                        return redirect()->back()->with('success', 'تم حذف الطرود');
                        break;
                    case '2':
                        if ($checks==null) {
                            return redirect()->back()->with('error','برجاء تحديد الطرود اللتي تود طباعتها');
                        }
                        if (count($checks)>260) {
                            return redirect()->back()->with('error','الحد الاقصى لطباعه الطرود 260 طرد');
                        }
                        foreach ($checks as $key => $value) {
                            $objectB = DB::table('orders')->select('*')->where('id', '=', $value)->get();
                            if ($objectB == null | $objectB == '[]') {
                            } else {
                                $obj_merged[$value] = $objectB[0];
                            }
                        }
                        $data = [
                            'title' => 'orders_'.date('ymd'),
                            'orders' => $obj_merged,
                            'boxbottom' => 40,
                        ];
                        $pdf = PDF::loadView('police', $data);
                        return $pdf->download('orders_print_' . date('y-m-d') . '.pdf');
                        break;
                }
                break;
        }
    }
}
