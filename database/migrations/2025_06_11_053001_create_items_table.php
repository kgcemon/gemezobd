<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Foreign Key: Relation with products table
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            // Item fields
            $table->string('name',35);
            $table->integer('price');
            $table->string('description')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->boolean('status')->default(1);
            $table->string('denom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
