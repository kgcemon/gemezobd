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
        Schema::create('notice_update', function (Blueprint $table) {
            $table->id();
            $table->longText('notice')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::table('notice_update')->insert([
            'notice'=> 'Hello this is a notice update.',
            'status'=> true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notice_update');
    }
};
