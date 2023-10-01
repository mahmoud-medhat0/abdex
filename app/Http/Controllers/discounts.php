<?php

namespace App\Http\Controllers;

use App\Rules\validemploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class discounts extends Controller
{
    public function __construct()
    {
        return $this->middleware(['auth','admin']);
    }
    public function discounts()
    {
        session()->flash('active', 'discounts');
        $discounts = DB::table('discounts')->selectRaw('discounts.id')
            ->selectRaw('discounts.cost')
            ->selectRaw('discounts.notes')
            ->selectRaw('discounts.created_at')
            ->selectRaw('discounts.id_user')->join('users', 'discounts.id_user', '=', 'users.id')
            ->selectRaw('users.name')
            ->get();
        return view('discounts.all', compact('discounts'));
    }
    public function discounts_add()
    {
        session()->flash('active', 'discounts_add');
        $emploies = DB::table('users')->select('id', 'name')->where('rank_id', '=', '1')
            ->orWhere('rank_id', '=', '2')
            ->orWhere('rank_id', '=', '3')
            ->orWhere('rank_id', '=', '4')
            ->orWhere('rank_id', '=', '5')
            ->orWhere('rank_id', '=', '6')
            ->orWhere('rank_id', '=', '7')
            ->get();
        return view('discounts.add', compact('emploies'));
    }
    public function discounts_store(Request $request)
    {
        $validate = [
            'employ' => ['required', new validemploy],
            'cost' => ['required', 'int'],
            'notes' => ['required']
        ];
        $request->validate($validate);
        DB::table('discounts')->insert([
            'cost' => $request['cost'],
            'id_user' => $request['employ'],
            'notes' => $request['notes']
        ]);
        $rank = DB::table('users')->select('id', 'rank_id')->where('id', '=', $request['employ'])->get()[0];
        switch ($rank->rank_id) {
            case '9':
                break;
            case '8':
                break;
            default:
                $discount = DB::table('salaries')->select('discount')->where('id_user', '=', $rank->id)->get()[0]->discount;
                $salaries = DB::table('salaries')->select('*')->where('id_user', '=', $rank->id)->update([
                    'discount' => $discount + $request['cost']
                ]);
                break;
        }
        $emploies = DB::table('users')->select('id', 'name')->get();
        return redirect()->back()->with('emploies', $emploies);
    }
}
