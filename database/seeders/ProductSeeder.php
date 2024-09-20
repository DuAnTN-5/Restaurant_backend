<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Phở bò', 'category_id' => 1, 'slug' => 'pho-bo', 'price' => 50000, 'ingredients' => 'Thịt bò, bánh phở', 'image_url' => 'pho_bo.jpg', 'description' => 'Phở bò truyền thống', 'product_code' => 'P001'],
            ['name' => 'Bánh mì', 'category_id' => 2, 'slug' => 'banh-mi', 'price' => 20000, 'ingredients' => 'Bánh mì, thịt nguội', 'image_url' => 'banh_mi.jpg', 'description' => 'Bánh mì thịt nguội', 'product_code' => 'P002'],
            ['name' => 'Heo Kobe', 'category_id' => 3, 'slug' => 'pho-bo', 'price' => 70000, 'ingredients' => 'Thịt bò, bánh phở', 'image_url' => 'pho_bo.jpg', 'description' => 'Phở bò truyền thống', 'product_code' => 'P003'],
            ['name' => 'Bò Alaska', 'category_id' => 2, 'slug' => 'banh-mi', 'price' => 60000, 'ingredients' => 'Bánh mì, thịt nguội', 'image_url' => 'banh_mi.jpg', 'description' => 'Bánh mì thịt nguội', 'product_code' => 'P004'],
            ['name' => 'Tôm hoàng đế', 'category_id' => 4, 'slug' => 'pho-bo', 'price' => 50000, 'ingredients' => 'Thịt bò, bánh phở', 'image_url' => 'pho_bo.jpg', 'description' => 'Phở bò truyền thống', 'product_code' => 'P005'],
            ['name' => 'Cháo gạo', 'category_id' => 4, 'slug' => 'banh-mi', 'price' => 80000, 'ingredients' => 'Bánh mì, thịt nguội', 'image_url' => 'banh_mi.jpg', 'description' => 'Bánh mì thịt nguội', 'product_code' => 'P006'],
            ['name' => 'Cơm chó', 'category_id' => 3, 'slug' => 'pho-bo', 'price' => 90000, 'ingredients' => 'Thịt bò, bánh phở', 'image_url' => 'pho_bo.jpg', 'description' => 'Phở bò truyền thống', 'product_code' => 'P007'],
            ['name' => 'Bún thịt nguội', 'category_id' => 2, 'slug' => 'banh-mi', 'price' => 80000, 'ingredients' => 'Bánh mì, thịt nguội', 'image_url' => 'banh_mi.jpg', 'description' => 'Bánh mì thịt nguội', 'product_code' => 'P008'],
        ]);
    }
}
