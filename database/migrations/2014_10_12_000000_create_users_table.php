<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable(); // Thêm cột image
            $table->string('phone_number')->nullable(); // Thêm cột phone_number
            $table->date('date_of_birth')->nullable(); // Thêm cột date_of_birth
            $table->string('sex', 10)->nullable(); // Thêm cột sex
            $table->string('role', 50)->nullable(); // Thêm cột role
            $table->string('province_code', 10)->nullable(); // Thêm cột province_code
            $table->string('district_code', 10)->nullable(); // Thêm cột district_code
            $table->string('ward_code', 10)->nullable(); // Thêm cột ward_code
            $table->string('address')->nullable(); // Thêm cột address
            $table->string('facebook_id', 100)->nullable(); // Thêm cột facebook_id
            $table->string('google_id', 100)->nullable(); // Thêm cột google_id
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
