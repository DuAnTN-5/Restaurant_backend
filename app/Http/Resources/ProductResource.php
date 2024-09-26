<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => new CategoryResource($this->category), // Mối quan hệ với danh mục
            'slug' => $this->slug,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity, // Thêm cột số lượng tồn kho
            'discount_price' => $this->discount_price, // Thêm cột giá giảm
            'availability' => $this->availability, // Thêm cột tình trạng sản phẩm
            'position' => $this->position, // Thứ tự sắp xếp
            'status' => $this->status, // Trạng thái sản phẩm (active, inactive, out-of-stock)
            'type' => $this->type, // Loại sản phẩm (food, beverage,...)
            'image_url' => $this->image_url,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
