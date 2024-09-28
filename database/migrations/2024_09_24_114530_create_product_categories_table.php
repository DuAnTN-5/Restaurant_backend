<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('name'); // Category Name
            $table->text('description')->nullable(); // Category Description
            $table->string('slug')->unique(); // URL-friendly Slug
            $table->unsignedBigInteger('parent_id')->nullable(); // Self-referencing foreign key for parent-child relationship
            $table->integer('position')->default(0); // Display order position
            $table->string('status')->default('active'); // Status (active, inactive)
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint: self-referencing to parent_id, set null on delete
            $table->foreign('parent_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
}
