<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Khai báo các trường có thể được ghi vào CSDL
    protected $fillable = [
        'name', 
        'description', 
        'price', 
        'category_id', 
        'type', 
        'image_url', 
        'stock_quantity', 
        'discount_price', 
        'availability',
        'position', // Nếu bạn có trường này trong bảng
        'status',   // Nếu bạn có trường này trong bảng
    ];

    // Thiết lập quan hệ với bảng product_categories
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
