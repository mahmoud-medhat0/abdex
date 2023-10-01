<?php

namespace App\Models;

use App\Models\Company;
use App\Models\CauseDelay;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $appends = ['printed'];
    protected $fillable = [
    'id','id_company',
    'id_police','name_client',
    'phone','phone2',
    'center_id','address',
    'cost','salary_charge',
    'date','notes',
    'special_intructions',
    'name_product','sender',
    'delegate_id','weghit',
    'premission_id','status_id',
    'cause_id','gps_delivered',
    'identy_number',
    ];
    public function companyname()
    {
        return $this->hasOne(Company::class,'id','id_company');
    }
    public function changername()
    {
        return $this->hasOne(User::class,'id','delegate_id');
    }
    public function getPrintedAttribute()
    {
        return DB::table('print_history')->where('order_id', $this->id)->count();
    }
    public function getOrderLocateNameAttribute()
    {
        switch ($this->order_locate) {
            case '0':
                return 'لم يتم الاستلام بعد';
                break;
            case '1':
                return 'بالمقر';
                break;
            case '2':
                return 'مع المندوب';
                break;
            case '3':
                return 'تم الرد للراسل';
                break;
            case '4':
                return 'مطلوب من المندوب';
                break;
            default :
                return __("none");
        }
    }
    public function delegatename()
    {
        return $this->hasOne(User::class,'id','delegate_id');
    }
    public function statename()
    {
        return $this->hasOne(OrderState::class,'id','status_id');
    }
    public function causereturn()
    {
        return $this->hasOne(CauseReturn::class,'id','cause_id');
    }
    public function delaycause()
    {
        return $this->hasOne(CauseDelay::class, 'id', 'delay_id');
    }
}
