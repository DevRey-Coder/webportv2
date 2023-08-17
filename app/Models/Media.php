<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ['filename','url'];

    public function photo(){
        return $this->hasOne(Photo::class);
    }

    public function media(){
        return $this->hasMany(Media::class);
    }
}
