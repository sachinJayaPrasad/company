<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = "employees";
    public function company(Request $request)
    {
        return $this->hasOne(\App\Models\Companies::class,'company_id','id');
    }
}
