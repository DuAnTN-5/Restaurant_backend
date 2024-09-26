<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostCategory;

class PostCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Công nghệ', 'slug' => 'cong-nghe', 'description' => 'Tin tức và bài viết về công nghệ mới nhất', 'position' => 1, 'status' => 'active'],
            ['name' => 'Ẩm thực', 'slug' => 'am-thuc', 'description' => 'Khám phá các món ăn ngon và công thức', 'position' => 2, 'status' => 'active'],
            ['name' => 'Du lịch', 'slug' => 'du-lich', 'description' => 'Những trải nghiệm và địa điểm du lịch hấp dẫn', 'position' => 3, 'status' => 'active'],
            ['name' => 'Sức khỏe', 'slug' => 'suc-khoe', 'description' => 'Bài viết về sức khỏe và lối sống lành mạnh', 'position' => 4, 'status' => 'active'],
        ];

        foreach ($categories as $category) {
            PostCategory::create($category);
        }
    }
}
