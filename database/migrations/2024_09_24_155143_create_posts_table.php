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
                $table->string('title'); // Tiêu đề bài viết
                $table->string('code')->unique(); // Mã code bài viết (phải duy nhất)
                $table->text('body')->nullable(); // Nội dung bài viết
                $table->text('summary')->nullable(); // Tóm tắt bài viết
                $table->string('image_url')->nullable(); // Đường dẫn ảnh của bài viết
                $table->unsignedBigInteger('user_id'); // Mối quan hệ với bảng users
                $table->unsignedBigInteger('category_id')->nullable(); // Mối quan hệ với bảng post_categories
                $table->string('status', 50)->default('draft'); // Tình trạng (published, draft, archived)
                $table->integer('position')->default(0); // Thứ tự sắp xếp
                $table->timestamps(); // Timestamps mặc định created_at, updated_at

                // Định nghĩa khóa ngoại cho user_id
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

                // Định nghĩa khóa ngoại cho category_id
                $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('set null');
            });
        }

        // Kiểm tra và thêm cột 'slug' nếu chưa tồn tại
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'slug')) {
                $table->string('slug')->unique()->after('title'); // Thêm slug nếu chưa tồn tại
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'slug')) {
                $table->dropColumn('slug'); // Xóa cột 'slug' khi rollback
            }
        });

        Schema::dropIfExists('posts');
    }
}
