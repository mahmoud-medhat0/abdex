<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    use HasFactory;
    protected $table="orders_history";
    public function changername()
    {
        return $this->hasOne(User::class,'id','user_id')??null;
    }
}
