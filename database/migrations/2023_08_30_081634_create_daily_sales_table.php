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
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('time')->nullable();
            $table->integer('vouchers')->nullable();
            $table->integer('dailyCash')->nullable();
            $table->integer('dailyTax')->nullable();
            $table->integer('dailyTotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};