<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class archieve3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archieve3:trig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'return orders to archieve3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = DB::table('orders')->where('status_id','=','3')
        ->where('order_locate','=','3')
        ->where('on_archieve','=','0')->select('id')->get();
        foreach ($orders as $order) {
            DB::table('orders')->where('id','=',$order->id)->update([
                'on_archieve'=>'1'
            ]);
        }
        return Command::SUCCESS;
    }
}
