<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySaleRecord extends Model
{
    protected $fillable = ['voucher_number','cash','tax','total','count','time'];
    use HasFactory;
}
