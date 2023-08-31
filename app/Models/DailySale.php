<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    protected $fillable = ['user_id', 'start', 'end', 'time', 'voucher_number', 'tax', 'cash', 'total','count'];

    use HasFactory;
}
