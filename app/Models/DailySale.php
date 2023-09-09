<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    protected $fillable = ['user_id', 'start', 'end', 'vouchers', 'dailyCash', 'dailyTax', 'dailyTotal','time'];

    use HasFactory;
}
