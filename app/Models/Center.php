<?php

namespace App\Models;

use App\Models\Governate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Center extends Model
{
    use HasFactory;
    protected $with=['governate'];
    public function governate()
    {
        return $this->hasOne(Governate::class,'id','governate_id');
    }
}
