<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = ["customer","phone","voucher_number","total","net_total","user_id","tax"];
    public function users(){
        return $this->belongsTo(User::class);
    }

    public function voucherRecords(){
        return $this->hasMany(VoucherRecord::class);
    }
}
