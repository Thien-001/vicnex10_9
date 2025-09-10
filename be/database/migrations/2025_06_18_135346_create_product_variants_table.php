<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('Variant_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->string('SKU')->unique();
            $table->string('Variant_name');
            $table->decimal('Price', 10, 2);
            $table->decimal('Discount_price', 10, 2)->nullable();
            $table->integer('Quantity')->default(0);
            $table->string('Image')->nullable();
            $table->tinyInteger('Status')->default(1);
            $table->timestamp('Created_at')->nullable();
            $table->timestamp('Update_at')->nullable();

            // Foreign key constraint
            $table->foreign('Product_ID')
                  ->references('Product_ID')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
