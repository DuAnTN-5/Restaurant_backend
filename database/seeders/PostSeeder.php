<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');
        $categories = [1, 2, 3, 4]; // Giả sử bạn có các ID này trong bảng danh mục bài viết
        $users = [1, 2]; // Giả sử bạn có một số user

        foreach (range(1, 10) as $index) {
            Post::create([
                'title' => 'Bài viết mẫu ' . $index,
                'body' => $faker->paragraph(5, true), // Nội dung bài viết
                'user_id' => $faker->randomElement($users),
                'category_id' => $faker->randomElement($categories),
                'slug' => 'bai-viet-mau-' . $index, // Tạo slug
                'status' => 'published', // Tình trạng bài viết
                'position' => $index,
                'image_url' => $faker->imageUrl(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
