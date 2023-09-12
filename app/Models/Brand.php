<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'company', 'information', 'user_id', 'photo'];
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function stocks()
    {
        return $this->hasManyThrough(Stock::class, Product::class, 'brand_id', 'product_id', 'id', 'id');
    }
}
