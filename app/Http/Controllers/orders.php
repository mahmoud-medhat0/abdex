<?php

namespace App\Http\Controllers;

use App\Imports\ordersimport;
use App\Imports\orderssearch;
use App\Models\Center;
use App\Models\Company;
use App\Models\order;
use App\Models\User;
use App\Rules\validagent;
use App\Rules\validcause;
use App\Rules\validcompany;
use App\Rules\ValidDelayId;
use App\Rules\validdelegate;
use App\Rules\ValidOrderLocate;
use App\Rules\ValidOrderLogic;
use App\Rules\validsatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Yajra\DataTables\DataTables;

class orders extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    public function new (Request $request)
    {
        session()->flash('active', 'neworders');
        $companies = DB::table('companies')->select('*')->get();
        $centers = DB::table('centers')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        return view('orders.addmanual')->with('companies', $companies)->with('centers', $centers)->with('agents', $agents)->with('delegates', $delegates);
    }

    public function orders(Request $request)
    {
        // $users = User::select(['id', 'name', 'created_at']);
        // return DataTables::of($users->get())
        // ->addColumn('formatted_created_at', function (User $user) {
        //     return $user->created_at->format('Y-m-d');
        // })
        // ->toJson();
        $query = order::query();

        // dd($request->all());
        if ($request->has('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($subquery) use ($searchValue) {
                $subquery->where(function ($nestedSubquery) use ($searchValue) {
                    $nestedSubquery->where('id', 'like', '%' . $searchValue . '%')
                        ->orWhere('id_police', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone', 'like', '%' . $searchValue . '%')
                        ->orWhere('phone2', 'like', '%' . $searchValue . '%')
                        ->orWhere('address', 'like', '%' . $searchValue . '%')
                        ->orWhere('cost', 'like', '%' . $searchValue . '%')
                        ->orWhere('notes', 'like', '%' . $searchValue . '%')
                        ->orWhere('special_intructions', 'like', '%' . $searchValue . '%')
                        ->orWhere('special_intructions2', 'like', '%' . $searchValue . '%')
                        ->orWhere('name_product', 'like', '%' . $searchValue . '%')
                        ->orWhere('sender', 'like', '%' . $searchValue . '%')
                        ->orWhereHas('companyname', function ($subquery) use ($searchValue) {
                            $subquery->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('delegatename', function ($subquery) use ($searchValue) {
                            $subquery->where('name', 'like', '%' . $searchValue . '%');
                        })
                        ->orWhereHas('statename', function ($sub) use ($searchValue) {
                            $sub->where('state', 'like', '%' . $searchValue . '%');
                        });
                });
                if ($subquery->orWhereHas('changername', function ($sub1query) use ($searchValue) {
                    $sub1query->where('name', 'like', '%' . $searchValue . '%');
                })->count() != 0) {
                    $subquery->orWhereHas('changername', function ($sub1query) use ($searchValue) {
                        $sub1query->where('name', 'like', '%' . $searchValue . '%');
                    });
                };
                // Add additional columns as needed for global search
            });
        }
        if ($request->has('id') && $request->input('id') != null) {
            $query->where('id', 'like', '%' . $request->input('id') . '%');
        }
        if ($request->has('id_police') && $request->input('id_police') != null) {
            $query->where('id_police', 'like', '%' . $request->input('id_police') . '%');
        }
        if ($request->has('name_client')) {
            $query->where('name_client', 'like', '%' . $request->input('name_client') . '%');
        }
        if ($request->has('phone') && $request->input('phone') != null) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }
        if ($request->has('phone2') && $request->input('phone2') != null) {
            $query->where('phone2', 'like', '%' . $request->input('phone2') . '%');
        }
        if ($request->has('createdat') && $request->input('createdat') != null) {
            $startTimestamp = Carbon::parse($request->input('createdat'));
            $query->where('created_at', '=', $startTimestamp);
        }
        if ($request->has('startdate') && $request->input('startdate') != null && $request->has('enddate') && $request->input('enddate') != null) {
            $startTimestamp = Carbon::parse($request->input('startdate'));
            $endTimestamp = Carbon::parse($request->input('enddate'));
            $query->where('delegate_delivered', '>=', $startTimestamp)
                ->where('delegate_delivered', '<=', $endTimestamp);
        }
        if ($request->has('address') && $request->input('address') != null) {
            $query->where('address', 'like', '%' . $request->input('address') . '%');
        }
        if ($request->has('cost') && $request->input('cost') != null) {
            $query->where('cost', 'like', '%' . $request->input('cost') . '%');
        }
        if ($request->has('date') && $request->input('date') != null) {
            $date = Carbon::parse($request->input('date'));
            $query->where('date', '=', $date);
        }
        if ($request->has('notes') && $request->input('notes') != null) {
            $query->where('notes', 'like', '%' . $request->input('notes') . '%');
        }
        if ($request->has('special_intructions') && $request->input('special_intructions') != null) {
            $query->where('special_intructions', 'like', '%' . $request->input('special_intructions') . '%');
        }
        if ($request->has('special_intructions2') && $request->input('special_intructions2') != null) {
            $query->where('special_intructions2', 'like', '%' . $request->input('special_intructions2') . '%');
        }
        if ($request->has('name_product') && $request->input('name_product') != null) {
            $query->where('name_product', 'like', '%' . $request->input('name_product') . '%');
        }
        if ($request->has('sender') && $request->input('sender') != null) {
            $query->where('sender', 'like', '%' . $request->input('sender') . '%');
        }
        if ($request->has('delay_date') && $request->input('delay_date') != null) {
            $delay_date = Carbon::parse($request->input('delay_date'));
            $query->where('delay_date', '=', $delay_date);
        }
        if ($request->has('special_intructions2') && $request->input('special_intructions2') != null) {
            $query->where('special_intructions2', 'like', '%' . $request->input('special_intructions2') . '%');
        }

        if ($request->has('companyname') && $request->input('companyname') != null) {
            $query->whereHas('companyname', function ($subquery) use ($request) {
                $subquery->where('name', 'like', '%' . $request->input('companyname') . '%');
            });
        }
        if ($request->has('delegatename') && $request->input('delegatename') != null) {
            $query->whereHas('delegatename', function ($subquery) use ($request) {
                $subquery->where('name', 'like', '%' . $request->input('delegatename') . '%');
            });
        }
        if ($request->has('statename') && $request->input('statename') != null) {
            $query->whereHas('statename', function ($sub) use ($request) {
                $sub->where('state', 'like', '%' . $request->input('statename') . '%');
            });
        }
        if ($request->has('identy_number') && $request->input('identy_number') != null) {
            $query->where('identy_number', 'like', '%' . $request->input('identy_number') . '%');
        }
        $orders = $query->where(function ($q) {
            $q->where('company_supply', '0')->where('on_archieve', '0');
        });
        $datatable = DataTables::of($orders->orderBy('created_at', 'desc')->get())
            ->addColumn('companyname', function (Order $order) {
                return $order->companyname = $order->companyname->name;
            })
            ->addColumn('print_count', function (Order $order) {
                return $order->printed;
            })
            ->addColumn('delegatename', function (Order $order) {
                return $order->delegatename = $order->delegatename ? $order->delegatename->name : __('none');
            })
            ->addColumn('delegate_deliver', function (Order $order) {
                return $order->delegate_delivered ? Carbon::parse($order->delegate_delivered)->format('Y-m-d H:m:s A') : 'none';
            })
            ->addColumn('created_atca', function (Order $order) {
                return Carbon::parse($order->created_at)->format('Y-m-d H:m:s A');
            })
            ->addColumn('orderlocatename', function (Order $order) {
                return $order->orderlocatename;
            })
            ->addColumn('statename', function (Order $order) {
                return $order->statename ? $order->statename->state : __('none');
            })
            ->addColumn('causereturn', function (Order $order) {
                return $order->causereturn->cause;
            })
            ->addColumn('delaycause', function (Order $order) {
                return $order->delaycause->cause;
            })
            ->addColumn('delegatesupplied', function (Order $order) {
                return $order->delegate_supply_date ? Carbon::parse($order->created_at)->format('Y-m-d h:m:s A') : "";
            })
            ->addColumn('companysupplied', function (Order $order) {
                return $order->company_supply_date ? Carbon::parse($order->created_at)->format('Y-m-d h:m:s A') : "";
            });
        return $datatable->toJson();
    }

    public function store_m(Request $request)
    {
        $validate = [
            'id_company' => ['required', new validcompany],
            'name_client' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'cost' => ['required', 'int'],
            'order_locate' => ['required', new ValidOrderLocate],
        ];
        $request->validate($validate);
        $special_intructions2 = DB::table('companies')->select('special_intructions')->where('id', '=', $request->id_company)->get()[0]->special_intructions;
        $data = [
            'id_company' => $request->id_company,
            'name_client' => $request->name_client,
            'phone' => $request->phone,
            'special_intructions2' => $special_intructions2,
            'date' => now(),
            'address' => $request['address'],
            'cost' => $request['cost'],

        ];
        $id = DB::table('orders')->insertGetId($data);
        DB::table('orders')
            ->where('id', $id)
            ->update([
                'id_police' => config('app.name') . '-' . $id,
            ]);

        if ($request['phone2'] == null) {
            $request['phone2'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'phone2' => $request['phone2'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'phone2' => $request['phone2'],
            ]);
        }
        if ($request['delegate_id'] == 'none' || $request['delegate_id'] == 'مندوب') {
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'delegate_id' => $request['delegate_id'],
            ]);
        }
        if ($request['notes'] == null) {
            $request['notes'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'notes' => $request['notes'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'notes' => $request['notes'],
            ]);
        }
        if ($request['special_intructions'] == null) {
            $request['special_intructions'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'special_intructions' => $request['special_intructions'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'special_intructions' => $request['special_intructions'],
            ]);
        }
        if ($request['name_product'] == null) {
            $request['name_product'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'name_product' => $request['name_product'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'name_product' => $request['name_product'],
            ]);
        }
        if ($request['sender'] == null) {
            $request['sender'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'sender' => $request['sender'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'sender' => $request['sender'],
            ]);
        }
        if ($request['weghit'] == null) {
            $request['weghit'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'weghit' => $request['weghit'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'weghit' => $request['weghit'],
            ]);
        }
        if ($request['open'] == null) {
            $request['open'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'open' => $request['open'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'open' => $request['open'],
            ]);
        }
        if ($request['identy_number'] == null) {
            $request['identy_number'] = 'none';
            DB::table('orders')->where('id', '=', $id)->update([
                'identy_number' => $request['identy_number'],
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'identy_number' => $request['identy_number'],
            ]);
        }
        if ($request['order_locate'] == 'select the order locate') {
            DB::table('orders')->where('id', '=', $id)->update([
                'order_locate' => '0',
                'cause_id' => '1',
                'status_id' => '10',
                'delay_id' => '1',
            ]);
        } else {
            DB::table('orders')->where('id', '=', $id)->update([
                'order_locate' => $request['order_locate'],
                'cause_id' => '1',
                'status_id' => '10',
                'delay_id' => '1',
            ]);
        }
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'add',
            'new' => json_encode(DB::table('orders')->latest('created_at')->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->back()->with('success', 'تمت اضافه الطرد بنجاح');
    }
    public function index()
    {
        session()->flash('active', 'orders_all');
        $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '0')->where('company_supply', '0')->orderBy('created_at', 'desc')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $printhistory = DB::table('print_history');
        // dd($printhistory->get());
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        session()->flash('orders', $orders);
        return view('orders.all')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes)
            ->with('printhistory', $printhistory);
    }

    public function indexcompanies($id)
    {
        session()->flash('active', 'orders_all');
        $orders = DB::table('orders')->where('id_company', $id)->where('order_locate', '0')->where('on_archieve', '=', '0')->orderBy('created_at', 'desc')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $printhistory = DB::table('print_history');
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        session()->flash('orders', $orders);
        return view('orders.companies')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes)
            ->with('printhistory', $printhistory);
    }

    public function ordersajax()
    {
        session()->flash('active', 'orders_all');
        $orders = DB::table('orders')->select('*')->where('on_archieve', '=', '0')->orderBy('created_at', 'desc')->get();
        $police_duplicate = array();
        $polices = array();
        foreach ($orders as $order) {
            if (in_array($order->id_police, $polices)) {
                array_push($police_duplicate, $order->id_police);
            } else {
                array_push($polices, $order->id_police);
            }
        }
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        session()->flash('orders', $orders);
        return view('orders.ordersajax')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes);
    }

    public function addorders()
    {
        session()->flash('active', 'addorders');
        $companies = DB::table('companies')->select('id', 'name')->get();
        return view('orders.add', compact('companies'));
    }
    public function storeorders()
    {
        request()->validate([
            'company' => ['required', 'exists:companies,id'],
            'sheet' => ['required', File::types(['xls', 'xlsx'])],
        ]);
        Excel::import(new ordersimport, request()->file('sheet'));
        $companies = DB::table('companies')->select('id', 'name')->get();
        return redirect()->back()->with('companies', $companies)->with('success', 'data imported succesfull to database');
    }
    public function checks(Request $request)
    {
        $method = $request['method'];
        switch ($method) {
            case 'state':
                $request->validate(['value' => ['required', new validsatus]]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'status_id' => $request['value'],
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                // dd($request);
                return redirect()->back()->with('success', 'تم تحديث حالات الطرود');
                break;
            case 'cause':
                $request->validate(['value' => ['required', new validcause]]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'cause_id' => $request['value'],
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                return redirect()->back()->with('success', 'تم تحديث سبب ارتجاع الطرود');
                break;
            case 'locate':
                $request->validate(['value' => ['required', 'in:0,1,2,3,4']]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'order_locate' => $request['value'],
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                return redirect()->back()->with('success', 'تم تحديث مندوب الطرود بنجاح');
                break;
            case 'destroy':
                $request->validate(['value' => ['required', 'in:1,2']]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                switch ($request['value']) {
                    case '1':
                        foreach ($checks as $key => $value) {
                            $order = DB::table('orders')->where('id', '=', $value);
                            $orderold = $order->get();
                            DB::table('orders_history')->insert([
                                'order_id' => $value,
                                'action' => 'delete',
                                'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                                'user_id' => auth()->user()->id,
                            ]);
                            $order->delete();
                        }
                        return redirect()->back()->with('success', 'تم حذف الطرود');
                        break;
                    case '2':
                        if ($checks == null) {
                            return redirect()->back()->with('error', 'برجاء تحديد الطرود اللتي تود طباعتها');
                        }
                        if (count($checks) > 260) {
                            return redirect()->back()->with('error', 'الحد الاقصى لطباعه الطرود 260 طرد');
                        }
                        foreach ($checks as $key => $value) {
                            DB::table('print_history')->insert([
                                'order_id' => $value,
                                'user_id' => auth()->user()->id,
                            ]);
                            $objectB = DB::table('orders')->select('*')->where('id', '=', $value)->get();
                            if ($objectB == null | $objectB == '[]') {
                            } else {
                                $obj_merged[$value] = $objectB[0];
                            }
                        }
                        $data = [
                            'title' => 'orders_' . date('ymd'),
                            'orders' => $obj_merged,
                            'boxbottom' => 40,
                        ];
                        $pdf = PDF::loadView('police', $data);
                        return $pdf->download('orders_print_' . date('y-m-d') . '.pdf');
                        break;
                    case '3':
                        if ($checks == null) {
                            return redirect()->back()->with('error', 'برجاء تحديد الطرود اللتي تود نقلها للارشيف');
                        }
                        foreach ($checks as $key => $value) {
                            $order = DB::table('orders')->where('id', '=', $value);
                            $orderold = $order->get();
                            DB::table('orders_history')->insert([
                                'order_id' => $value,
                                'action' => 'delete',
                                'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                                'user_id' => auth()->user()->id,
                            ]);
                            $order->update([
                                'on_archieve'=>'1'
                            ]);
                        }
                        return redirect()->back()->with('success', 'تم نقل الطرود الى الارشيف بنجاح');
                        break;
                }
                break;
            case 'agent':
                $request->validate(['value' => ['required', new validagent]]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'agent_id' => $request['value'],
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                return redirect()->back()->with('success', 'تم تغير وكلاء الطرود');
                break;
            case 'delegate':
                $request->validate(['value' => ['required', new validdelegate]]);
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'delegate_id' => $request['value'],
                        'order_locate' => '2',
                        'delegate_delivered' => now(),
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                return redirect()->back()->with('success', 'تم تغيير مناديب الطرود');
                break;
            case 'company':
                $request->validate(['value' => ['required', new validcompany]]);
                if (auth()->user()->rank_id != 1) {
                    return redirect()->back()->with('error', 'user not allowed');
                }
                $checks = $request->except('_token', 'example1_length', 'method', 'value', 'checkbox', 'checkbox-undefined');
                foreach ($checks as $key => $value) {
                    $validate[$key] = ['required', new ValidOrderLogic];
                    $request->validate($validate);
                    $order = DB::table('orders')->where('id', '=', $value);
                    $orderold = $order->get();
                    $order->update([
                        'id_company' => $request['value'],
                    ]);
                    DB::table('orders_history')->insert([
                        'order_id' => $value,
                        'action' => 'edit',
                        'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
                        'new' => json_encode($order->get()[0], JSON_UNESCAPED_UNICODE),
                        'user_id' => auth()->user()->id,
                    ]);
                }
                return redirect()->back()->with('success', 'تم تغيير شركات الطرود');
                break;
        }
    }
    public function edit($id)
    {
        $order = DB::table('orders')->select('*')->where('id', '=', $id)->get()[0];
        $companies = DB::table('companies')->select('*')->get();
        $centers = DB::table('centers')->select('*')->get();
        $delegates = DB::table('users')->select('id', 'name')->where('rank_id', '=', '9')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $causes_delay = DB::table('causes_delay')->select('*')->get();
        return view('orders.edit')
            ->with('order', $order)
            ->with('companies', $companies)
            ->with('centers', $centers)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('causes_delay', $causes_delay);
    }
    public function update(Request $request)
    {
        $validate['id'] = ['required', new ValidOrderLogic];
        $validate['delay_id'] = ['required', new ValidDelayId];
        $validate['order_locate'] = ['required', new ValidOrderLocate];
        $request->validate($validate);
        $id = request()->input('id');
        $order = DB::table('orders')->select('*')->where('id', '=', $id)->get()[0];
        $orderupdate = DB::table('orders')->select('*')->where('id', '=', $id);
        $orderold = $orderupdate->get();
        if ($request['phone'] != null) {
            if ($request['phone'] != "none") {
                $orderupdate->update([
                    'phone' => $request['phone'],
                ]);
            }
        } else {
            $orderupdate->update([
                'phone' => 'none',
            ]);
        }
        if ($request['phone2'] != null) {
            if ($request['phone2'] != "none") {
                $orderupdate->update([
                    'phone2' => $request['phone2'],
                ]);
            }
        } else {
            $orderupdate->update([
                'phone2' => 'none',
            ]);
        }
        if ($request['delegate_id'] != "0") {
            $validate['delegate_id'] = [new validdelegate];
            request()->validate($validate);
            $orderupdate->update([
                'delegate_id' => $request['delegate_id'],
            ]);
        } else {
            $orderupdate->update([
                'delegate_id' => null,
            ]);
        }
        if ($request['address'] == null) {
            $orderupdate->update(['address' => "none"]);
        } else {
            $orderupdate->update(['address' => $request['address']]);
        }
        if ($request['cost'] == null) {
            $orderupdate->update([
                'cost' => 'none',
            ]);
        } else {
            $orderupdate->update(['cost' => $request['cost']]);
        }
        if ($request['cost'] == null) {
            $orderupdate->update([
                'cost' => 'none',
            ]);
        } else {
            $orderupdate->update(['cost' => $request['cost']]);
        }
        if ($request['notes'] == null) {
            $orderupdate->update([
                'notes' => 'none',
            ]);
        } else {
            $orderupdate->update(['notes' => $request['notes']]);
        }
        if ($request['special_intructions'] == null) {
            $orderupdate->update([
                'special_intructions' => 'none',
            ]);
        } else {
            $orderupdate->update(['special_intructions' => $request['special_intructions']]);
        }
        if ($request['name_product'] == null) {
            $orderupdate->update([
                'name_product' => 'none',
            ]);
        } else {
            $orderupdate->update(['name_product' => $request['name_product']]);
        }
        if ($request['sender'] == null) {
            $orderupdate->update([
                'sender' => 'none',
            ]);
        } else {
            $orderupdate->update(['sender' => $request['sender']]);
        }
        if ($request['weghit'] == null) {
            $orderupdate->update([
                'weghit' => 'none',
            ]);
        } else {
            $orderupdate->update(['weghit' => $request['weghit']]);
        }
        if ($request['open'] == null) {
            $orderupdate->update([
                'open' => 'none',
            ]);
        } else {
            $orderupdate->update(['open' => $request['open']]);
        }
        if ($request['status_id'] != "none") {
            $validate['status_id'] = ['required', new validsatus];
            request()->validate($validate);
            $orderupdate->update(['status_id' => $request['status_id']]);
        } else {
            $orderupdate->update(['status_id' => null]);
        }
        if ($request['cause_id'] != "none") {
            $validate['cause_id'] = ['required', new validcause];
            request()->validate($validate);
            $orderupdate->update(['cause_id' => $request['cause_id']]);
        } else {
            $orderupdate->update(['cause_id' => null]);
        }
        if ($request['order_locate'] != "none") {
            $validate['order_locate'] = ['in:0,1,2,3,4'];
            request()->validate($validate);
            $orderupdate->update([
                'order_locate' => $request['order_locate'],
            ]);
        } else {
            $orderupdate->update(['order_locate' => null]);
        }
        if ($request['identy_number'] == null) {
            $orderupdate->update([
                'identy_number' => 'none',
            ]);
        } else {
            $orderupdate->update(['identy_number' => $request['identy_number']]);
        }
        if ($request['company_supply'] == null || $request['company_supply'] == '0') {
            $orderupdate->update([
                'company_supply' => 0,
            ]);
        } else {
            $validate['company_supply'] = ['in:0,1'];
            $request->validate($validate);
            $orderupdate->update([
                'company_supply' => $request['company_supply'],
                'company_supply_date' => now(),
            ]);
        }
        if ($request['delegate_supply'] == null || $request['delegate_supply'] == '0') {
            $orderupdate->update([
                'delegate_supply' => 0,
            ]);
        } else {
            $validate['delegate_supply'] = ['in:0,1'];
            $request->validate($validate);
            $orderupdate->update([
                'delegate_supply' => $request['delegate_supply'],
                'delegate_supply_date' => now(),
            ]);
        }
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'edit',
            'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
            'new' => json_encode($orderupdate->get()[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id,
        ]);
        return redirect()->route('orders_all')->with('order', $order)->with('success', 'order updated successful');
    }
    public function stamp()
    {
        return response()->download(public_path('excel/test.xlsx'), 'orders_stamp.xlsx');
    }
    public function stamp_sub()
    {
        return response()->download(public_path('excel/stamp_sub.xlsx'), 'sub_stamp.xlsx');
    }
    public function delete($id)
    {
        $order = DB::table('orders')->select('*')->where('id', '=', $id);
        $orderold = $order->get();
        DB::table('orders_history')->insert([
            'order_id' => $id,
            'action' => 'delete',
            'old' => json_encode($orderold[0], JSON_UNESCAPED_UNICODE),
            'user_id' => auth()->user()->id,
        ]);
        $order->delete();
        $orders = DB::table('orders')->select('*')->get();
        $centers = DB::table('centers')->select('*')->get();
        $companies = DB::table('companies')->select('*')->get();
        return redirect()->back()->with('success', 'order deleted successful')->with('orders', $orders)->with('centers', $centers)->with('companies', $companies);
    }
    public function orderssearch()
    {
        if (request()->method() === 'GET') {
            $orders = [];
            foreach (Cache::get('orders') as $order) {
                $orders[$order->id] = DB::table('orders')->where('id', $order->id)->select('*')->get()[0];
            }
            // dd($orders);
            session()->flash('orders', $orders);
            $companies = DB::table('companies')->select('*')->get();
            $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
            $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
            $states = DB::table('order_state')->select('*')->get();
            $causes = DB::table('causes_return')->select('*')->get();
            $printhistory = DB::table('print_history');
            $police_duplicate = array();
            $polices = array();
            $delayes = DB::table('causes_delay')->select('*')->get();
            return view('orders.all')
                ->with('companies', $companies)
                ->with('agents', $agents)
                ->with('delegates', $delegates)
                ->with('states', $states)
                ->with('causes', $causes)
                ->with('police_duplicate', $police_duplicate)
                ->with('delayes', $delayes)
                ->with('printhistory', $printhistory);
        }
        request()->validate([
            'sheet' => ['required', File::types(['xls', 'xlsx'])],
        ]);
        Excel::import(new orderssearch, request()->file('sheet'));
        $companies = DB::table('companies')->select('*')->get();
        $agents = DB::table('users')->select('id', 'name')->where('rank_id', '=', '8')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $printhistory = DB::table('print_history');
        $police_duplicate = array();
        $polices = array();
        $delayes = DB::table('causes_delay')->select('*')->get();
        if (session()->get('orders') != null) {
            Cache::put('orders', session()->get('orders'), 99999); // $minutes indicates the duration for which the data should be stored
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        return view('orders.search')
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes)
            ->with('printhistory', $printhistory);
    }
    public function print($id)
    {
        $orders = DB::table('orders')->where('id', $id)->get();
        $data = [
            'title' => date('y-m-d'),
            'orders' => $orders,
        ];
        $title = date('y-m-d');
        $orders = $orders;
        DB::table('print_history')->insert([
            'order_id' => $id,
            'user_id' => auth()->user()->id,
        ]);
        $pdf = PDF::loadView('police', $data);
        return $pdf->download('order_' . $id . '.pdf');
    }
    public function orders_delegate()
    {
        $centers = Center::all();
        $companies = Company::all();
        $agents = User::where('rank_id', '9')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        if (session()->get('orders') != null) {
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        return view('orders.orders_delegate')->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes);
    }
    public function orders_delegate_search($id)
    {
        session()->flash('active', 'orders.delegates');
        $centers = Center::all();
        $companies = Company::all();
        $agents = User::where('rank_id', '9')->get();
        $delegates = DB::table('users')->select('id', 'name', 'commision')->where('rank_id', '=', '9')->get();
        $states = DB::table('order_state')->select('*')->get();
        $causes = DB::table('causes_return')->select('*')->get();
        $delayes = DB::table('causes_delay')->select('*')->get();
        $police_duplicate = array();
        $polices = array();
        if (session()->get('orders') != null) {
            foreach (session()->get('orders') as $order) {
                if (in_array($order->id_police, $polices)) {
                    array_push($police_duplicate, $order->id_police);
                } else {
                    array_push($polices, $order->id_police);
                }
            }
        }
        if (isset($id)) {
            $orders = DB::table('orders')->where('delegate_id', $id)->where('delegate_supply', '0')->whereNot('status_id', '3')->get();
        } else {
            $orders = DB::table('orders')->get();
        }

        session()->flash('orders', $orders);
        return view('orders.orders_delegate')
            ->with('centers', $centers)
            ->with('companies', $companies)
            ->with('agents', $agents)
            ->with('delegates', $delegates)
            ->with('states', $states)
            ->with('causes', $causes)
            ->with('police_duplicate', $police_duplicate)
            ->with('delayes', $delayes)
            ->with('id', $id);
    }
}
