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
        Schema::create('payment_sms', function (Blueprint $table) {
            $table->id();
            $table->string('order_number',30)->nullable();
            $table->string('sender',30);
            $table->string('number',15);
            $table->string('trxID',30)->unique();
            $table->string('amount');
            $table->enum('status',[0,1])->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_sms');
    }
};
