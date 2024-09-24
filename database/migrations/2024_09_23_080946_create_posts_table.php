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
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('category_id')->nullable();
                $table->string('status', 50)->default('published'); // Tình trạng (published, draft, archived)
                $table->integer('position')->default(0); // Thứ tự sắp xếp
                $table->string('image_url')->nullable();
                $table->timestamps();

                // Định nghĩa khóa ngoại
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
