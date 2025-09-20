<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('support_country',30)->nullable();
            $table->string('delivery_system',40)->nullable();
            $table->longText('description')->nullable();
            $table->string('short_description',200)->nullable();
            $table->string('tags')->nullable();
            $table->string('keywords')->nullable();
            $table->string('input_name');
            $table->string('input_others')->nullable();
            $table->integer('total_input')->default(1);
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_auto')->default(false);
            $table->integer('sort')->default(0);
            $table->boolean('stock')->default(1);
            $table->integer('status')->default(true);
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->timestamps();
        });

        DB::table('products')->insert([
            'category_id' => 1,
            'name' => 'Wallet',
            'slug' => 'wallet',
            'support_country' => 'US',
            'delivery_system' => 'US',
            'description' => 'Wallet',
            'short_description' => 'Wallet',
            'tags' => 'wallet',
            'keywords' => 'wallet',
            'input_name' => 'wallet',
            'input_others' => 'wallet',
            'image' => '/wallet.png',
            'cover_image' => 'wallet.png',
            'sort' => 1,
            'status' => 5,
            'stock' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
