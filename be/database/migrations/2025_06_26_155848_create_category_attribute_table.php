<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_attribute', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Sử dụng đúng tên cột PK từ 2 bảng
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('attribute_id');

            $table->timestamps();

            // FK chính xác
            $table->foreign('category_id')
                ->references('Categories_ID')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('attribute_id')
                ->references('Attributes_ID')
                ->on('product_attributes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_attribute');
    }
};
