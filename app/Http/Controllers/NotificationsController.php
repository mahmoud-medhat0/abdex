<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function notifications()
    {
        $companies = Company::all();
        $notifications = Notification::where('notifiable_id',auth()->user()->id)->orderBy('created_at', 'desc')->get();
        foreach ($notifications as $notification) {
            foreach ($companies as $company) {
                if (strpos($notification->title,$company->name)) {
                    $notification->company_id = $company->id;
                }
            }
            $notification->created_at_date = Carbon::parse($notification->created_at)->format('Y-m-d');
            $notification->created_at_time = Carbon::parse($notification->created_at)->format('h:i:s A');
        }
        $unread = Notification::where('notifiable_id',auth()->user()->id)->where('read_at',null)->get()->count();
        return response()->json(['unread'=>$unread,'notifications'=>$notifications]);
    }
    public function read()
    {
        $notifications = Notification::where('notifiable_id',auth()->user()->id)->update([
            'read_at'=>now()
        ]);
        return response()->json(['success'=>'success']);
    }
}
