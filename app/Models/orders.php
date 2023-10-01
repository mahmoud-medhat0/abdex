<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        'id',
        'id_company',
        'id_police',
        'name_client',
        'phone',
        'phone2',
        'center_id',
        'address',
        'cost',
        'salary_charge',
        'date',
        'notes',
        'special_intructions',
        'name_product',
        'sender',
        'delegate_id',
        'weghit',
        'premission_id',
        'status_id',
        'cause_id',
        'gps_delivered',
        'identy_number',
    ];
}
