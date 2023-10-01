<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        App::setLocale('ar');
        session()->put('lang', 'ar');
        switch (Auth::user()->rank_id) {
            case '1':
                session()->flash('active', 'null');
                session()->put('parent', 'parent1');
                // $money = DB::table('keep_money')->latest('created_at')->get()[0]->moneyafter;
                // if ($money == null) {
                //     $money = 0;
                // }
                // $profits = DB::table('profits')->latest('created_at')->get()[0]->moneyafter;
                $profitsout = DB::table('profits_out')->latest('created_at')->get()[0]->moneyafter;
                $sum_agents = DB::table('account_stament_agents')->where('confirm', '1')->sum('payed');
                $sum_companies = DB::table('account_stament_companies')->sum('payed');
                $sum_accounts = DB::table('accounts_financial')->where('confirm', '1')->sum('creditor');
                $sum_accounts_debtor = DB::table('accounts_financial')->where('confirm', '1')->sum('debtor');
                //
                $sum_orders = DB::table('orders')->where('company_supply', '=', '1')->sum('company_commission');
                $sum_delegates_orders = DB::table('orders')->where('delegate_supply', '=', '1')->join('users', 'orders.delegate_id', '=', 'users.id')->sum('users.commision');
                $salaries = DB::table('salaries')->where('done', '=', '1')->sum('salary');
                $discounts = DB::table('salaries')->where('done', '=', '1')->sum('discount');
                $company_expenses = DB::table('company_expenses')->sum('cost');
                $profits = ($sum_orders - $sum_delegates_orders) + $profitsout - ($salaries - $discounts) - $company_expenses;
                $money = ($sum_agents - $sum_companies) + ($sum_accounts - $sum_accounts_debtor) - ($salaries - $discounts) - $company_expenses;
                //temp money
                $sum_accounts_temp = DB::table('accounts_financial')->where('confirm', '0')->sum('creditor');
                $sum_accounts_debtor_temp = DB::table('accounts_financial')->where('confirm', '0')->sum('debtor');
                $sum_agents_temp = DB::table('account_stament_agents')->where('confirm', '0')->sum('payed');
                $tempmoney = ($sum_accounts_temp - $sum_accounts_debtor_temp) + $sum_agents_temp;
                //salaries
                $salaries_under = DB::table('salaries')->where('done', '0')->sum('salary');
                //expenses
                $company_out = DB::table('company_expenses')->where('type_out', '0')->sum('cost');
                $company_personal = DB::table('company_expenses')->where('type_out', '1')->sum('cost');
                $company_moving = DB::table('company_expenses')->where('type_out', '2')->sum('cost');
                $company_consists = DB::table('company_expenses')->where('type_out', '3')->sum('cost');
                return view('home')->with('money', $money)
                    ->with('profits', $profits)
                    ->with('profitsout', $profitsout)
                    ->with('tempmoney', $tempmoney)
                    ->with('salaries_under', $salaries_under)
                    ->with('company_out', $company_out)
                    ->with('company_personal', $company_personal)
                    ->with('company_moving', $company_moving)
                    ->with('company_consists', $company_consists);
                break;
            case '2':
                return redirect(route('customerservice'));
                break;
            case '3':
                return redirect(route('operation'));
                break;
            case '5':
                return redirect(route('accountant'));
                break;
            case '7':
                return redirect(route('orderscompany'));
                break;
            case '8':
                return redirect(route('agent'));
                break;
            default:
                # code...
                break;
        }
    }
    public function notificaion()
    {
        return view('notification');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAA64zg2YA:APA91bHTg-oujmX3KzLw5Oq0gaL12ji3zUS50fjaflbPZ5_cA9VEoSNJxxwYYKmXIOb-Do11Co0aKAe4T68GGZs2CPSsscyKt4lWyNA-lo9zo8v43HbGZlFj1c9fLfa7dQI5ANz-VORd';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "content_available" => true,
                "priority" => "high",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
        $r0 = json_decode($response);
        if ($r0->success == '0') {
            return redirect()->back()->with('error','Failed to send notification');
        }
        else{
            return redirect()->back()->with('success','Done');
        }
    }
}
