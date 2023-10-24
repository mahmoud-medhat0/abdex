<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\User;
use App\Models\Center;
use App\Rules\validuid;
use App\Rules\validcause;
use App\Rules\validcompany;
use App\Rules\ValidDelayId;
use App\Rules\validorderid;
use App\Models\Notification;
use App\Rules\OrderEditable;
use Illuminate\Http\Request;
use App\Rules\ClientHasOreder;
use App\Rules\OrderDeleteable;
use App\Rules\ValidOrderState;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class orders extends Controller
{
    public function __construct()
    {
        App::setLocale('ar');
        return $this->middleware('auth:sanctum');
    }
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', request()->uid)->get()[0]->user_id;
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $userid)->get()[0]->rank_id;
        switch ($rank) {
            case '8':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')->where('agent_id', '=', $userid)->where('on_archieve', '0')->select('*')
                    ->selectRaw('orders.id AS orderid')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')
                    ->get();
                $results =array();
                foreach ($orders as $order) {
                    foreach ($order as $key => $value) {
                        if (str_contains($value, request()->data)) {
                            array_push($results, $order);
                        }
                    }
                }
                return response()->json($results, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            case '9':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->select('*')
                    ->selectRaw('orders.id AS orderid')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')
                    ->get();
                $results =array();
                foreach ($orders as $order) {
                    foreach ($order as $key => $value) {
                        if (str_contains($value, request()->data)) {
                            array_push($results, $order);
                        }
                    }
                }
                return response()->json($results, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            default:
                return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
                break;
        }
    }
    public function all(Request $request)
    {
    switch (Auth::user()->rank_id) {
        case '7':
            $orders = DB::table('orders')
                ->where('on_archieve', '0')->where('company_supply','0')
                ->where('id_company', Auth::user()->company_id)
                ->select('*')
                ->selectRaw('orders.id AS orderid')
                ->join('order_state', 'order_state.id', '=', 'orders.status_id')
                ->selectRaw('order_state.state AS statename')
                ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')
                ->selectRaw('causes_return.cause AS causename')
                ->join('companies', 'companies.id', '=', 'orders.id_company')
                ->selectRaw('companies.name AS company_name')
                ->get()
                ->map(function ($item) {
                    switch ($item->order_locate) {
                        case '0':
                            $item->locate_name = 'لم يتم التحديد بعد';
                            break;
                        case '1':
                            $item->locate_name = 'بمقر الشركة';
                            break;
                        case'2':
                            $item->locate_name = 'خرج مع المندوب';
                            break;
                        case'3':
                            $item->locate_name = 'تم الرد للراسل';
                            break;
                        case'4':
                            $item->locate_name = 'مطلوب من المندوب';
                            break;
                        }
                    return $item;
                });
            return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
            break;
        case '9':
            $userid = Auth::user()->id;
            $orders = DB::table('orders')->where('on_archieve', '0')->where('delegate_id', '=', $userid)
                ->whereNot('order_locate','3')->whereNot('order_locate','4')
                ->select('*')->selectRaw('orders.id AS orderid')
                ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get()
                ->map(function ($item) {
                    switch ($item->order_locate) {
                        case '0':
                            $item->locate_name = 'لم يتم التحديد بعد';
                            break;
                        case '1':
                            $item->locate_name = 'بمقر الشركة';
                            break;
                        case'2':
                            $item->locate_name = 'خرج مع المندوب';
                            break;
                        case'3':
                            $item->locate_name = 'تم الرد للراسل';
                            break;
                        case'4':
                            $item->locate_name = 'مطلوب من المندوب';
                            break;
                        }
                    return $item;
                });
            return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
            break;
        default:
            return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
            break;
        }
    }
    public function store_m(Request $request){
        $validate = [
            'uid' =>['required',new validuid()],
            'name_client' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'cost' => ['required', 'int'],
        ];
        $validator = Validator::make($request->all(),$validate);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id','active')->where('uid', '=', $request->uid)->get()[0];
        $user = DB::table('users')->select('id', 'rank_id','company_id')->where('id', '=', $userid->user_id)->get()[0];
        $data = [
            'id_company' => Auth::user()->company_id,
            'name_client' => $request->name_client,
            'phone' => $request->phone,
            'date' => now(),
            'address' => $request['address'],
            'cost' => $request['cost'],

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
        DB::table('orders')->where('id', '=', $id)->update([
                'order_locate' => '0',
                'cause_id' => '1',
                'status_id' => '10',
                'delay_id'=>'1'
            ]);
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'add',
            'new' => json_encode(DB::table('orders')->latest('created_at')->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id
        ]);
        $currentDate = Carbon::today()->toDateString();
        $companyname = DB::table('companies')->where('id',Auth()->user()->company_id)->select('name')->get()[0]->name;
        $title = 'تمت اضافة طرود من شركة '.$companyname;
        Notification::whereDate('created_at', $currentDate)->where('title',$title)->delete();
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
            $notification1->notifiable_id = auth()->user()->id;
            $notification1->data = $title;
            $notification1->notifiable_type = 'user';
            $notification1->save();
            $notification1->created_at->format('Y-m-d H:i:s');
            $notification1->created_at_date = $notification1->created_at->format('Y-m-d');
            $notification1->created_at_time = $notification1->created_at->format('h:i:s A');
            $pusher->trigger('notifications', 'OrderUpdate', $notification1);
        return response()->json(['success'=>'تمت إضافة الطرد بنجاح'], 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function delete(Request $request)
    {
        $validate=[
            'orderid'=>['required',new ClientHasOreder(),new OrderDeleteable()],
            'uid' =>['required',new validuid()],
        ];
        $validator = Validator::make($request->all(),$validate);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        try {
            DB::table('orders_history')->insert([
                'order_id' => $request->orderid,
                'action' => 'delete',
                'old'=> json_encode(DB::table('orders')->where('id',$request->orderid)->get()[0], JSON_UNESCAPED_UNICODE),
                'user_id' => auth()->user()->id
            ]);
            DB::table('orders')->where('id',$request->orderid)->delete();
            return response()->json(['success'=>'تم حذف الطرد بنجاح.'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e], 422,[], JSON_UNESCAPED_UNICODE);
        }
    }
    public function update(Request $request)
    {
        $validate=[
            'uid' =>['required',new validuid()],
            'orderid'=>['required',new ClientHasOreder(),new OrderEditable()],
            'name_client' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'cost' => ['required', 'int'],
        ];
        $validator = Validator::make($request->all(),$validate);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        try {
            $orderold = DB::table('orders')->where('id',$request->orderid)->select('*')->get();
            DB::table('orders')
            ->where('id',$request->orderid)
            ->update([
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
            $order = DB::table('orders')->where('id',$request->orderid)->select('*')->get();
            DB::table('orders_history')->insert([
                'order_id' => $request->orderid,
                'action' => 'update by delegate',
                'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                'new' => json_encode($order[0], JSON_UNESCAPED_UNICODE),
                'user_id' => Auth::user()->id
            ]);

            return response()->json(['success'=>'تم تحديث الطرد بنجاح.'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e], 422,[], JSON_UNESCAPED_UNICODE);
        }

    }
    public function delivered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', $request->uid)->get()[0]->user_id;
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $userid)->get()[0]->rank_id;
        switch ($rank) {
            case '9':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')
                    ->where('on_archieve', '0')->select('*')->where('status_id', '=', '1')
                    ->orWhere('status_id', '=', '2')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '3')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '4')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '5')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '9')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '7')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->orWhere('status_id', '=', '8')->where('delegate_id', '=', $userid)->where('on_archieve', '0')
                    ->selectRaw('orders.id AS orderid')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get();
                return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            default:
                return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
                break;
        }

    }
    public function returned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', $request->uid)->get()[0]->user_id;
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $userid)->get()[0]->rank_id;
        switch ($rank) {
            case '8':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')
                    ->where('on_archieve', '0')->where('agent_id', '=', $userid)
                    ->orwhereIn('status_id',['2','3','4','5','7','8','9'])
                    ->select('*')->selectRaw('orders.id AS orderid')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get();
                return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            case '9':
                $orders = DB::table('orders')->where('on_archieve', '0')->where('delegate_id', '=', $userid)
                    ->whereNot('order_locate','3')->whereNot('order_locate','4')
                    ->where('status_id','2')->orwhereIn('status_id',['3','4','5','7','8','9'])
                    ->select('*')->selectRaw('orders.id AS orderid')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_return', 'causes_return.id', '=', 'orders.cause_id')->selectRaw('causes_return.cause AS causename')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get();
                return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            default:
                return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
                break;
        }
    }
    public function delayed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', $request->uid)->get()[0]->user_id;
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $userid)->get()[0]->rank_id;
        switch ($rank) {
            case '8':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')->where('on_archieve', '0')->select('*')->where('agent_id', '=', $userid)->selectRaw('orders.id AS orderid')->where('status_id', '=', '6')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_delay', 'causes_delay.id', '=', 'orders.delay_id')->selectRaw('causes_delay.cause AS delayname')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get();
                return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            case '9':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')->where('on_archieve', '0')->select('*')->where('delegate_id', '=', $userid)->selectRaw('orders.id AS orderid')->where('status_id', '=', '6')
                    ->join('order_state', 'order_state.id', '=', 'orders.status_id')->selectRaw('order_state.state AS statename')
                    ->join('causes_delay', 'causes_delay.id', '=', 'orders.delay_id')->selectRaw('causes_delay.cause AS delayname')
                    ->join('companies', 'companies.id', '=', 'orders.id_company')->selectRaw('companies.name AS company_name')->get();
                return response()->json($orders, 200, [], JSON_UNESCAPED_UNICODE);
                break;
            default:
            return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
                break;
        }
    }
    public function causes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $causes = DB::table('causes_return')->select('*')->get();
        return response()->json($causes, 200, [], JSON_UNESCAPED_UNICODE);
    }

    //store section
    public function store_delivered(Request $request)
    {
        $rules = [
            'uid'=>['required',new validuid()],
            'order_id'=>['required',new validorderid()],
            'status_id'=>['required',new ValidOrderState()],
            'gps_delivered'=>['required','string']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $order = DB::table('orders')->select('*')->where('id', '=', $request->order_id);
        $orderold = $order->get();
        $order->update([
            'status_id' => $request->status_id,
            'gps_delivered' => $request->gps_delivered
        ]);
        if($request->status_id == '2') {
            foreach(User::whereNot('rank_id', '7')->get() as $user) {
                $notification = new Notification();
                $notification->title = 'order '.$request->order_id.' updated';
                $notification->notifiable_id = $user->id;
                $notification->data = 'order '.$request->order_id.' updated by '.auth()->user()->name;
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
            $notification1->title = 'order '.$request->order_id.' updated';
            $notification1->notifiable_id = auth()->user()->id;
            $notification1->data = 'order '.$request->order_id.' updated by '.auth()->user()->name;
            $notification1->notifiable_type = 'user';
            $notification1->save();
            $pusher->trigger('notifications', 'OrderUpdate', $notification1);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', $request->uid)->get()[0]->user_id;
        DB::table('orders_history')->insert([
            'order_id' => $request->order_id,
            'action' => 'update by delegate',
            'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
            'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => $userid
        ]);
        return response()->json(['success' => 'تم تحديث حاله الطرد بنجاح'], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function money(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' =>['required',new validuid()]
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>'false','orders'=>'','error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $userid = DB::table('uid')->select('user_id')->where('uid', '=', $request->uid)->get()[0]->user_id;
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $userid)->get()[0]->rank_id;
        switch ($rank) {
            case '8':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')
                    ->where('on_archieve', '0')->select('*')->where('agent_id', '=', $userid)->where('status_id', '=', '1')
                    ->orWhere('status_id', '=', '2')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '3')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '4')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '5')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '9')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '7')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '8')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->join('users','users.id','orders.delegate_id');
                    return response()->json(['delivered'=>$orders->sum('orders.cost'),'comission'=>$orders->sum('users.commision'),'total'=>$orders->sum('orders.cost')-($orders->sum('users.commision')* $orders->count()),'orders_count'=>$orders->count()], 200, [], JSON_UNESCAPED_UNICODE);
                    break;
            case '9':
                $orders = DB::table('orders')->whereNot('order_locate','3')->whereNot('order_locate','4')->where('on_archieve', '0')->select('*')->where('delegate_id', '=', $userid)->where('status_id', '=', '1')
                    ->orWhere('status_id', '=', '2')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '4')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '5')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '9')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '7')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->orWhere('status_id', '=', '8')->where('delegate_id', '=', $userid)->where('on_archieve', '0')->where('delegate_supply','0')
                    ->join('users','users.id','orders.delegate_id');
                return response()->json(['delivered'=>$orders->sum('orders.cost'),'comission'=>$orders->sum('users.commision'),'total'=>$orders->sum('orders.cost')-($orders->sum('users.commision')* $orders->count()),'orders_count'=>$orders->count()], 200, [], JSON_UNESCAPED_UNICODE);
                break;
            default:
                return response()->json(['error'=>'غير مسموح لك بالدخول'], 422,[], JSON_UNESCAPED_UNICODE);
                break;
        }
    }
    public function delegates_request(Request $request)
    {
        $rules = [
            'uid'=>['required',new validuid()],
            'company_id'=>['required',new validcompany()],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->toArray()], 422,[], JSON_UNESCAPED_UNICODE);
        }
        $name = DB::table('companies')->where('id',$request->company_id)->select('name')->get()[0]->name;
        foreach(User::whereNot('rank_id', '7')->get() as $user) {
            $notification = new Notification();
            $notification->title = $name.' delegates';
            $notification->notifiable_id = $user->id;
            $notification->data =$name.' wants delegate '.auth()->user()->name;
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
        $notification1->title = $name.' delegates';
        $notification1->notifiable_id = auth()->user()->id;
        $notification1->data = $name.' wants delegate '.auth()->user()->name;
        $notification1->notifiable_type = 'user';
        $notification1->save();
        $pusher->trigger('notifications', 'OrderUpdate', $notification1);
        return response()->json([
            'success'=>'تم ارسال الاشعار بنجاح'
        ]);
    }
    public function centers()
    {
        return response()->json(['centers'=>Center::all()], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
