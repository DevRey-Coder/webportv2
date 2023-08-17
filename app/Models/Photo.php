<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = ['name','url','ext','user_id','media_id'];
    public function media(){
        return $this->belongsTo(Media::class);
    }
}
