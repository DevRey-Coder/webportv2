<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    protected $fillable = ['date','tax','net_total', 'status'];
    use HasFactory;

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }
}
