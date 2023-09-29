<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucherRecords()
    {
        return $this->hasMany(VoucherRecord::class);
    }
    public function recordedProducts()
    {
        return $this->belongsToMany(Product::class, VoucherRecord::class);
    }


    protected $fillable = ["customer", "phone", "voucher_number", "total", "tax", "net_total", "user_id",'total_actual_price'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


}
