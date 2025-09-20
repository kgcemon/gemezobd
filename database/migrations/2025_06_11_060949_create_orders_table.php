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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name',20)->nullable();
            $table->string('phone',14)->nullable();
            $table->string('email',70)->nullable();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('item_id')->nullable();
            $table->integer('quantity');
            $table->float('total');
            $table->string('customer_data');
            $table->string('others_data')->nullable();
            $table->enum('status',['hold','processing','Delivery Running','delivered','cancelled','refunded'])->default('hold');
            $table->string('order_note')->nullable();
            $table->unsignedInteger('payment_method');
            $table->string('transaction_id')->unique()->nullable();
            $table->string('number')->nullable();
            $table->string('uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
