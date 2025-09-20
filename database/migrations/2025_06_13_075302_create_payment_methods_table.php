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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('method',15);
            $table->string('description')->nullable();
            $table->string('number',13)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        DB::table('payment_methods')->insert([
            'icon' => '/wallet.png',
            'method' => 'Wallet',
            'description' => 'Cash',
            'number' => 1,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
