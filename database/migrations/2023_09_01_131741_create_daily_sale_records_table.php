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
        Schema::create('daily_sale_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->nullable();
            $table->string('time')->nullable();
            $table->integer('cash')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('total')->nullable();
            $table->integer('count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sale_records');
    }
};
