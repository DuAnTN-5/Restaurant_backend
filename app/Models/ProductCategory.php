<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'position', 'status'
    ];

    // Mối quan hệ với danh mục cha
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Mối quan hệ với danh mục con
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
}
