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
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('item_id')->constrained('items');
            $table->string('code',120)->unique();
            $table->enum('status',['used','unused'])->default('unused');
            $table->string('order_id')->nullable();
            $table->integer('denom')->nullable();
            $table->boolean('active')->default(true);
            $table->string('uid',70)->unique()->nullable();
            $table->string('note',70)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
