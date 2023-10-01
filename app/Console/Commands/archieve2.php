<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class archieve2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archieve2:trig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'return orders to archieve2';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = DB::table('orders')->where('status_id','=','2')
        ->orWhere('status_id','=','4')
        ->orWhere('status_id','=','5')
        ->orWhere('status_id','=','7')
        ->orWhere('status_id','=','8')
        ->where('order_locate','=','3')
        ->where('delegate_supply','=','1')
        ->where('company_supply','=','1')
        ->where('on_archieve','=','0')->select('id')->get();
        foreach ($orders as $order) {
            DB::table('orders')->where('id','=',$order->id)->update([
                'on_archieve'=>'1'
            ]);
        }
        return Command::SUCCESS;
    }
}
