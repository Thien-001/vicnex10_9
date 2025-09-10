<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('Product_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedTinyInteger('Rating'); // 1-5
            $table->timestamps();

            $table->unique(['Product_ID', 'User_ID']);
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ratings');
    }
};
