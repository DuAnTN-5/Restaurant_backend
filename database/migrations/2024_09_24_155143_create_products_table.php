<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Sử dụng phương thức id() để tạo cột `id` là unsigned big integer
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('category_id')->nullable(); // Thêm cột category_id
            $table->string('type', 50)->nullable()->comment('Type of product, e.g., "food" or "beverage"');
            $table->string('image_url')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('availability')->default(true);
            $table->integer('position')->default(0); // Thứ tự sắp xếp
            $table->string('status', 50)->default('active'); // Tình trạng (active, inactive, out-of-stock)
            $table->timestamps();

            // Định nghĩa khóa ngoại
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
}
