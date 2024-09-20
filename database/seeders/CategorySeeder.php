<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Món ăn chính', 'slug' => 'mon-an-chinh', 'description' => 'Món ăn chính của nhà hàng'],
            ['name' => 'Món ăn phụ', 'slug' => 'mon-an-phu', 'description' => 'Món ăn phụ'],
            ['name' => 'Món ăn nhẹ', 'slug' => 'mon-an-nhe', 'description' => 'Món ăn nhẹ'],
            ['name' => 'Đồ uống', 'slug' => 'do-uong', 'description' => 'Đồ uống các loại'],
        ]);
    }
}
