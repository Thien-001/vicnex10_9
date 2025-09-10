<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_variant_values', function (Blueprint $table) {
            $table->bigIncrements('Product_variant_values_ID'); // PK

            // FK -> product_variants(Variant_ID)
            $table->unsignedBigInteger('Variant_ID');
            $table->foreign('Variant_ID')->references('Variant_ID')->on('product_variants')->onDelete('cascade');

            // FK -> product_values(Values_ID)
            $table->unsignedBigInteger('Values_ID');
            $table->foreign('Values_ID')->references('Values_ID')->on('product_values')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_values');
    }
};
