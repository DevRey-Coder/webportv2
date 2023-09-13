<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherRecord extends Model
{
    use HasFactory;

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand()
    {
        return $this->hasManyThrough(Brand::class, Product::class);
    }
    protected $fillable = ["voucher_id", "total_cost", "items"];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}
