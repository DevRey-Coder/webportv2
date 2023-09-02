<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesRecords extends Model
{
    protected $fillable = ['daily_sale_id','voucher_id'];
    use HasFactory;

    public function dailySale()
    {
        return $this->belongsTo(DailySale::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
