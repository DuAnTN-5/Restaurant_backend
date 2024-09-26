<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');
        $categories = [1, 2, 3, 4]; // Giả sử bạn có các ID này trong bảng danh mục sản phẩm

        foreach (range(1, 30) as $index) {
            Product::create([
                'name' => 'Sản phẩm ' . $index,
                'description' => 'Mô tả cho sản phẩm ' . $index . ' với nhiều tính năng đặc biệt.',
                'price' => $faker->numberBetween(10000, 100000), // Giá từ 10.000 đến 100.000 VND
                'category_id' => $faker->randomElement($categories),
                'type' => $index % 2 == 0 ? 'food' : 'beverage', // Ví dụ phân loại sản phẩm
                'image_url' => $faker->imageUrl(),
                'stock_quantity' => $faker->numberBetween(0, 100), // Số lượng tồn kho
                'discount_price' => $faker->optional()->randomFloat(2, 5000, 50000), // Giá khuyến mãi
                'availability' => $faker->boolean(), // Tình trạng có sẵn
                'position' => $index,
                'slug' => 'san-pham-' . $index, // Tạo slug
                'status' => 'active', // Tình trạng sản phẩm
            ]);
        }
    }
}
