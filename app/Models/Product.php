<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'brand_id', 'actual_price', 'sale_price', 'total_price', 'unit', 'more_information', 'user_id', 'photo'];
    use HasFactory;
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function brands()
    {
        return $this->belongsTo(Brand::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function voucherRecord()
    {
        return $this->hasOne(VoucherRecord::class);
    }
    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, VoucherRecord::class);
    }
}
