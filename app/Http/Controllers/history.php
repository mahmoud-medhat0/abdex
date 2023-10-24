<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\order;

class history extends Controller
{
    private $keyMappings =[];
    public $replacedOldValues=[];
    public $replacedNewValues=[];
    public function __construct()
    {
        $this->keyMappings = [
            'status_id' => 'حالة الطرد',
            'gps_delivered' => 'مكان التسليم',
            'updated_at' => 'وقت اخر تحديث',
            'notes' => 'المذكرات',
            'delay_id' => 'سبب التأجيل',
            'delay_date' => 'تاريخ التأجيل',
            'cause_id' => 'سبب الارتجاع',
            'order_locate'=>'مكان الطرد',
            'phone'=>'رقم الهاتف الاول',
            'phone2'=>'رقم الهاتف الثاني',
            'delegate_id'=>'اسم المندوب',
            'address'=>'العنوان',
            'cost'=>'السعر',
            'special_intructions'=>'تعليمات خاصة 1',
            'special_intructions2'=>'تعليمات خاصة 2',
            'name_product'=>'اسم المنتج',
            'sender'=>'الراسل',
            'weghit'=>'الوزن',
            'open'=>'الفتح',
            'identy_number'=>'رقم البطاقة'
            ];

        return $this->middleware(['auth','RankParent']);
    }
    public function print_history()
    {
        session()->flash('print_history');
        $data = DB::table('print_history')->join('users','users.id','print_history.user_id')
        ->select('print_history.*','users.name')->get();
        return view('history.print')->with('data',$data);
    }
    public function print_historyc($id)
    {
        session()->flash('print_history');
        $data = DB::table('print_history')->where('order_id',$id)->join('users','users.id','print_history.user_id')
        ->select('print_history.*','users.name')->get();
        return view('history.print')->with('data',$data);
    }
    public function orders_added(){
        session()->flash('active', 'orders_added');
        $data = DB::table('orders_history')->where('action','add')
            ->select('orders_history.id AS sid','orders_history.action','orders_history.created_at','orders_history.order_id', 'users.name','orders_history.old','orders_history.new')
            ->join('users', 'users.id', '=', 'orders_history.user_id')
            ->orderBy('orders_history.created_at', 'desc')
            ->get();
        $newvalues = [];
        foreach ($data as $order) {
            $new = json_decode($order->new);
            $newvalues[$order->sid] = [];
            foreach ($new as $key => $value) {
                $newvalues[$order->sid][$key] = [$new->$key][0];
            }
        }
        $replacedNewValues = [];
        foreach ($newvalues as $sid => $new) {
            $replacedNewValues[$sid] = [];
            foreach ($newvalues[$sid] as $key => $values) {
                if (isset($this->keyMappings[$key])) {
                    $replacedKey = $this->keyMappings[$key];
                    try {
                        if ($key =='status_id' && $values==null) {
                            DB::table('orders_history')->where('id',$sid)->delete();
                        }
                        if($values>0 && $values!=null) {
                            $replacedValue = $this->getReplacedValue($key, $values);
                            $replacedNewValues[$sid][$replacedKey] = [$replacedValue];
                        }
                    } catch (\Exception $e) {
                        dd($e,$key,$sid,$values);
                    }
                }
            }
        }
        return view('history.ordersadd')
            ->with('data', $data)
            ->with('newvalues', $replacedNewValues);
    }
    public function orders_deleted(){
        session()->flash('active', 'orders_deleted');
        $data = DB::table('orders_history')->where('action','delete')
            ->select('orders_history.id AS sid','orders_history.action','orders_history.created_at','orders_history.order_id', 'users.name','orders_history.old','orders_history.new')
            ->join('users', 'users.id', '=', 'orders_history.user_id')
            ->orderBy('orders_history.created_at', 'desc')
            ->get();
        $oldvalues = [];
        foreach ($data as $order) {
            $old = json_decode($order->old);
            $oldvalues[$order->sid] = [];
            foreach ($old as $key => $value) {
                $oldvalues[$order->sid][$key] = [$old->$key][0];
            }
        }
        $replacedoldValues = [];
        foreach ($oldvalues as $sid => $old) {
            $replacedoldValues[$sid] = [];
            foreach ($oldvalues[$sid] as $key => $values) {
                if (isset($this->keyMappings[$key])) {
                    $replacedKey = $this->keyMappings[$key];
                    try {
                        if ($key =='status_id' && $values==null) {
                            DB::table('orders_history')->where('id',$sid)->delete();
                        }
                        if($values>0 && $values!=null) {
                            $replacedValue = $this->getReplacedValue($key, $values);
                            $replacedoldValues[$sid][$replacedKey] = [$replacedValue][0];
                        }
                    } catch (\Exception $e) {
                        dd($e,$key,$sid,$values);
                    }
                }
            }
        }
        return view('history.ordersdelete')
            ->with('data', $data)
            ->with('oldvalues', $replacedoldValues);
    }
    public function restore($id)
    {
        $data = DB::table('orders_history')->where('action','delete')->where('order_id',$id)
        ->select('orders_history.id AS sid','orders_history.action','orders_history.created_at','orders_history.order_id', 'users.name','orders_history.old','orders_history.new')
        ->join('users', 'users.id', '=', 'orders_history.user_id')
        ->orderBy('orders_history.created_at', 'desc')
        ->get();
        // dd(json_decode($data[0]->old));
        DB::table('orders')->insert((array) json_decode($data[0]->old));
        return redirect()->back()->with('success','order '.$data[0]->order_id);
    }
    public function orders_history()
    {
        session()->flash('active', 'orders_history');
        return view('history.orders');
    }
    public function history_ajax(Request $request)
    {
        $query = OrderHistory::query()->where('action','edit');
        if ($request->has('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($subquery) use ($searchValue) {
                $subquery->where(function ($nestedSubquery) use ($searchValue) {
                    $nestedSubquery->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('order_id', 'like', '%' . $searchValue . '%')
                        ->orWhere('old', 'like', '%' . $searchValue . '%')
                        ->orWhere('new', 'like', '%' . $searchValue . '%');
                });
                if ($subquery->orWhereHas('changername', function ($sub1query) use ($searchValue) {
                    $sub1query->where('name', 'like', '%' . $searchValue . '%');
                })->count()!=0) {
                    $subquery->orWhereHas('changername', function ($sub1query) use ($searchValue) {
                        $sub1query->where('name', 'like', '%' . $searchValue . '%');
                    });
                };
                // Add additional columns as needed for global search
            });
        }
        $data = $query->get();
        if ($data!="[]") {
            foreach ($data as $order) {
            $old = json_decode($order->old);
            $new = json_decode($order->new);
            $oldvalues = [];
            $newvalues = [];
            $oldvalues[$order->sid] = [];
            $newvalues[$order->sid] = [];

            foreach ($old as $key => $value) {
                    try {
                        if ($old->$key != $new->$key) {
                            $oldvalues[$order->sid][$key] = [$old->$key];
                            $newvalues[$order->sid][$key] = [$new->$key];
                        }
                    } catch (\Exception $e) {
                        DB::table('orders_history')->where('id',$order->sid)->delete();
                }
            }
        }
        $replacedOldValues = [];
        $replacedNewValues = [];
        foreach ($oldvalues as $sid => $old) {
            $replacedOldValues[$sid] = [];
            $replacedNewValues[$sid] = [];

            foreach ($old as $key => $values) {
                if (isset($this->keyMappings[$key])) {
                    $replacedKey = $this->keyMappings[$key];
                    $replacedValue = $this->getReplacedValue($key, $values[0]);
                    $replacedOldValues[$sid][$replacedKey] = [$replacedValue];
                }
            }
            foreach ($newvalues[$sid] as $key => $values) {
                if (isset($this->keyMappings[$key])) {
                    $replacedKey = $this->keyMappings[$key];
                    $replacedValue = $this->getReplacedValue($key, $values[0]);
                    $replacedNewValues[$sid][$replacedKey] = [$replacedValue];
                }
            }
        }
        $this->replacedOldValues = $replacedOldValues;
        $this->replacedNewValues = $replacedNewValues;

        }
        $datatable = DataTables::of($data)
        ->addColumn('oldmain',function () {
            return $this->replacedOldValues[""];
        })
        ->addColumn('newmain',function () {
            return $this->replacedNewValues[""];
        })
        ->addColumn('idmain',function ($data) {
            return $data['id'];
        })->addColumn('changername',function($data){
            return User::FindOrFail($data['user_id']) ? User::FindOrFail($data['user_id'])->name : "";
        })
        ->addColumn('created_atca',function($data){
            return Carbon::parse($data['created_at'])->format('Y-m-d H:m:s A');
         });
        return $datatable->toJson();

    }
    private function getReplacedValue($key, $value)
    {
        $statusMappings = DB::table('order_state')->pluck('state', 'id')->all();
        $delayMappings = DB::table('causes_delay')->pluck('cause', 'id')->all();
        $causeMappings = DB::table('causes_return')->pluck('cause', 'id')->all();
        $delegates = DB::table('users')->where('rank_id','9')->pluck('name','id')->all();
        $orderlocates = [
                '0'=>'لم يتم التحديد بعد',
                '1'=>'بالمقر',
                '2'=>'مع المندوب',
                '3'=>'تم الرد للراسل',
                '4'=>'مطلوب من المندوب'
            ];
        switch ($key) {
            case 'status_id':
                return isset($statusMappings[$value]) ? $statusMappings[$value] : $value;
            case 'cause_id':
                return isset($causeMappings[$value]) ? $causeMappings[$value] : $value;
            case 'delay_id':
                return isset($delayMappings[$value]) ? $delayMappings[$value] : $value;
            case 'order_locate':
                return isset($orderlocates[$value]) ? $orderlocates[$value] : $value;
            case 'delegate_id':
                return isset($delegates[$value]) ? $delegates[$value] : $value;
            default:
                return $value;
        }
    }
    public function history_arcieve()
    {
        session()->flash('active', 'history_arcieve');
        return view('history.archieve');
    }
    public function archieve_ajax(Request $request)
    {
        $query = order::query()->where('delegate_supply','1')->where('company_supply','1')->orWhere('on_archieve','1')->select('*');
        if ($request->has('id')) {
            $query->where('id', 'like', '%' . $request->input('id') . '%');
        }
        if ($request->has('id_police')) {
            $query->where('id_police', 'like', '%' . $request->input('id_police') . '%');
        }
        if ($request->has('name_client')) {
            $query->where('name_client', 'like', '%' . $request->input('name_client') . '%');
        }
        if ($request->has('phone') && $request->input('phone')!=null) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }
        if ($request->has('phone2') && $request->input('phone2')!=null) {
            $query->where('phone2', 'like', '%' . $request->input('phone2') . '%');
        }
        if ($request->has('createdat') && $request->input('createdat')!=null) {
            $startTimestamp = Carbon::parse($request->input('createdat'));
            $query->where('created_at', '=', $startTimestamp);
        }
        if ($request->has('start_timestamp') && $request->input('start_timestamp')!=null && $request->has('end_timestamp') && $request->input('end_timestamp')!=null) {
            $startTimestamp = Carbon::parse($request->input('start_timestamp'));
            $endTimestamp = Carbon::parse($request->input('end_timestamp'));
            $query->where('delegate_delivered', '>=', $startTimestamp)
                  ->where('delegate_delivered', '<=', $endTimestamp);
        }
        if ($request->has('address') && $request->input('address')!=null) {
            $query->where('address', 'like', '%' . $request->input('address') . '%');
        }
        if ($request->has('cost') && $request->input('cost')!=null) {
            $query->where('cost', 'like', '%' . $request->input('cost') . '%');
        }
        if ($request->has('date') && $request->input('date')!=null) {
            $date = Carbon::parse($request->input('date'));
            $query->where('date', '=',$date);
        }
        if ($request->has('notes') && $request->input('notes')!=null) {
            $query->where('notes', 'like', '%' . $request->input('notes') . '%');
        }
        if ($request->has('special_intructions') && $request->input('special_intructions')!=null) {
            $query->where('special_intructions', 'like', '%' . $request->input('special_intructions') . '%');
        }
        if ($request->has('special_intructions2') && $request->input('special_intructions2')!=null) {
            $query->where('special_intructions2', 'like', '%' . $request->input('special_intructions2') . '%');
        }
        if ($request->has('name_product') && $request->input('name_product')!=null) {
            $query->where('name_product', 'like', '%' . $request->input('name_product') . '%');
        }
        if ($request->has('sender') && $request->input('sender')!=null) {
            $query->where('sender', 'like', '%' . $request->input('sender') . '%');
        }
        if ($request->has('delay_date') && $request->input('delay_date')!=null) {
            $delay_date = Carbon::parse($request->input('delay_date'));
            $query->where('delay_date', '=',$delay_date);
        }
        if ($request->has('special_intructions2') && $request->input('special_intructions2')!=null) {
            $query->where('special_intructions2', 'like', '%' . $request->input('special_intructions2') . '%');
        }

        if($request->has('companyname') && $request->input('companyname') !=null){
            $query->whereHas('companyname', function ($subquery) use ($request) {
                $subquery->where('name', 'like','%'.$request->input('companyname').'%');
            });
        }
        if($request->has('delegatename') && $request->input('delegatename')!= null){
            $query->whereHas('delegatename', function ($subquery) use ($request) {
                $subquery->where('name','like','%'.$request->input('delegatename').'%');
            });
        }
        if($request->has('statename') && $request->input('statename')!=null){
            $query->whereHas('statename',function($sub) use ($request){
                $sub->where('state','like','%'.$request->input('statename').'%');
            });
        }
        if($request->has('identy_number') && $request->input('identy_number')!=null){
            $query->where('identy_number', 'like', '%' . $request->input('identy_number') . '%');
        }
        if($request->has('nameproduct') && $request->input('nameproduct')!=null){
            $query->where('name_product', 'like', '%' . $request->input('nameproduct') . '%');
        }
        if($request->has('sender') && $request->input('sender')!=null){
            $query->where('sender', 'like', '%' . $request->input('sender') . '%');
        }
        if($request->has('weghit') && $request->input('weghit')!=null){
            $query->where('weghit', 'like', '%' . $request->input('weghit') . '%');
        }
        if($request->has('sender') && $request->input('sender')!=null){
            $query->where('sender', 'like', '%' . $request->input('sender') . '%');
        }
        if($request->has('cause_return') && $request->input('cause_return')!= null){
            $query->whereHas('causereturn', function ($subquery) use ($request) {
                $subquery->where('cause','like','%'.$request->input('cause_return').'%');
            });
        }
        $datatable = DataTables::of($query->get())
        ->addColumn('printed', function (Order $order) {
            return $order->printed;
         })
         ->addColumn('companyname',function(Order $order){
            return $order->companyname = $order->companyname->name ;
         })
         ->addColumn('delegatename',function(Order $order){
            return $order->delegatename = $order->delegatename ? $order->delegatename->name :__('none');
         })
         ->addColumn('created_atca',function(Order $order){
            return Carbon::parse($order->created_at)->format('Y-m-d h:m:s A');
         })
         ->addColumn('orderlocatename',function(Order $order){
           return $order->orderlocatename;
         })
         ->addColumn('statename',function(Order $order){
            return $order->statename ? $order->statename->state :__('none');
         })
         ->addColumn('causereturn',function(Order $order){
            return $order->causereturn->cause;
         })
         ->addColumn('delaycause',function(Order $order){
            return $order->delaycause->cause;
         })
         ->addColumn('delegatesupplied',function(Order $order){
            return $order->delegate_supply_date ? Carbon::parse($order->created_at)->format('Y-m-d h:m:s A'):"";
         })
         ->addColumn('companysupplied',function(Order $order){
            return $order->company_supply_date ? Carbon::parse($order->created_at)->format('Y-m-d h:m:s A'):"";
         });
        return $datatable->toJson();
    }
    // public function users_history()
    // {
    //     session()->flash('active', 'users_history');
    //     $data = DB::table('users_history')->select('*')
    //     ->join('users','users.id','=','users_history.user_id')->selectRaw('users.name')
    //     ->selectRaw('users_history.id AS sid')->get();
    //     $oldvalues = array();
    //     $newvalues = array();
    //     foreach ($data as $order =>$value) {
    //     if (!is_null($value->old)&& !is_null($value->new)) {
    //         $old = json_decode($value->old);
    //         $new = json_decode($value->new);
    //         $oldvalues[$value->sid] = array();
    //         $newvalues[$value->sid] = array();
    //         foreach ($old as $old1=>$value1) {
    //          if ($old->$old1!=$new->$old1) {
    //             $oldvalues[$value->sid][$old1] = array();
    //             // $oldvalues[$value->sid][$old1][$old->$old1] = array();
    //             $newvalues[$value->sid][$old1] = array();
    //             // $newvalues[$value->sid][$old1][$new->$old1] = array();
    //             array_push($oldvalues[$value->sid][$old1], $old->$old1);
    //             array_push($newvalues[$value->sid][$old1], $new->$old1);
    //          }
    //         }
    //     }elseif(!isset($value->old)&&$value->old==null&&isset($value->new)&&$value->old!=null){
    //         $old=null;
    //         $new = json_decode($value->new);
    //         $oldvalues[$value->sid] = null;
    //         $newvalues[$value->sid] = array();
    //         foreach ($new as $old1=>$value1) {
    //           // dd($old1,$value1);
    //             $oldvalues[$value->sid][$old1] = array();
    //             // $oldvalues[$value->sid][$old1][$old->$old1] = array();
    //             $newvalues[$value->sid][$old1] = array();
    //             // $newvalues[$value->sid][$old1][$new->$old1] = array();
    //             array_push($newvalues[$value->sid][$old1], $new->$old1);
    //         }
    //     }elseif(!isset($value->new)&&$value->new==null&&isset($value->old)&&$value->old!=null){
    //         $old = json_decode($value->old);
    //         $new = null;
    //         $oldvalues[$value->sid] = array();
    //         $newvalues[$value->sid] = null;
    //         foreach ($old as $old1=>$value1) {
    //             // dd($old1,$value1);
    //             $oldvalues[$value->sid][$old1] = array();
    //             // $oldvalues[$value->sid][$old1][$old->$old1] = array();
    //             $newvalues[$value->sid][$old1] = array();
    //             // $newvalues[$value->sid][$old1][$new->$old1] = array();
    //             array_push($oldvalues[$value->sid][$old1], $old->$old1);
    //         }

    //     }
    //     }
    //     return view('history.users')->with('data', $data)->with('oldvalues', $oldvalues)->with('newvalues', $newvalues);
    // }
}
