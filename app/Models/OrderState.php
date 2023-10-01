<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderState extends Model
{
    protected $table = "order_state";
    use HasFactory;
    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
