<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class cost0 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cost0:trig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update cost to 0';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = DB::table('orders')
        ->where('status_id','=','5')
        ->where('on_archieve','=','0')->select('id')->get();
        foreach ($orders as $order) {
            DB::table('orders')->where('id','=',$order->id)->update([
                'cost'=>'0'
            ]);
        }
        return Command::SUCCESS;
    }
}
