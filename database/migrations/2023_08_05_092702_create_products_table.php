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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('name');
            $table->foreignId('brand_id'); // Assumes the 'brands' table exists
            $table->decimal('actual_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->bigInteger('total_price');
            $table->char('unit');
            $table->text('more_information'); // Changed to 'text' data type to store longer information
            $table->foreignId('user_id'); // Assumes the 'users' table exists
            $table->char('photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
