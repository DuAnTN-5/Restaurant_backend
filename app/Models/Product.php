<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'slug', 'price', 'ingredients', 'image_url', 'description', 'product_code'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
