<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    // Khai báo bảng liên kết
    protected $table = 'product_categories';

    // Các cột có thể được phép ghi dữ liệu
    protected $fillable = [
        'name',
        'description',
        'slug',
        'position',
        'status',
        'created_at',
        'updated_at'
    ];

    // Quan hệ với model Product (nhiều sản phẩm thuộc một danh mục)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
