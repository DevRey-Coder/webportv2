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
            $table->char("name");
            $table->foreignId("brand_id");
            $table->bigInteger("actual_price");
            $table->bigInteger("sale_price");
            $table->bigInteger("total_price");
            $table->char("unit");
            $table->bigInteger("more_information");
            $table->foreignId("user_id");
            $table->char("photo");
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
