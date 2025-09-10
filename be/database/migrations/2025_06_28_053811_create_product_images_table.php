<?php

// database/migrations/xxxx_xx_xx_create_product_images_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('Image_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->string('Image_path');
            $table->timestamps();

            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_images');
    }
};
