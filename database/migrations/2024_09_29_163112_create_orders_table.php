<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_type', 50);
            $table->timestamp('order_date')->useCurrent();
            $table->decimal('total_price', 10, 2);
            $table->text('note')->nullable();
            $table->string('payment_status', 50);
            $table->string('status', 50);
            $table->text('delivery_address')->nullable();
            $table->timestamp('estimated_delivery_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
