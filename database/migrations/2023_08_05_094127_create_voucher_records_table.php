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
        Schema::create('voucher_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id');
//            ->constrained()->cascadeOnDelete();
            $table->foreignId('product_id');
            $table->string('time')->nullable();

            $table->integer('quantity');
            $table->double('cost');
            $table->double('price');
            $table->double('actual_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_records');
    }
};
