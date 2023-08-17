<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    // protected $fillable = ['name','url','ext','user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
