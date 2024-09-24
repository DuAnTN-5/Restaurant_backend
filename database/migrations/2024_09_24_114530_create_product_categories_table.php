<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id(); // Sử dụng phương thức id() để tạo cột `id` là unsigned big integer
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->integer('position')->default(0);
            $table->string('status')->default('active'); // Tình trạng (active, inactive)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
}
