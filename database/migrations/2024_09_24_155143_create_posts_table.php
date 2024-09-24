<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('body')->nullable();
                $table->unsignedBigInteger('user_id'); // Mối quan hệ với bảng users
                $table->unsignedBigInteger('category_id')->nullable(); // Mối quan hệ với bảng post_categories
                $table->string('status', 50)->default('published'); // Tình trạng (published, draft, archived)
                $table->integer('position')->default(0); // Thứ tự sắp xếp
                $table->string('image_url')->nullable();
                $table->timestamps();

                // Định nghĩa khóa ngoại cho user_id
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                // Định nghĩa khóa ngoại cho category_id
                $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
}