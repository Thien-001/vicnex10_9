<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('Cart_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Product_ID');
            $table->integer('Quantity')->default(1);
            $table->decimal('Price', 10, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('User_ID')
                  ->references('ID')->on('user')
                  ->onDelete('cascade');

            $table->foreign('Product_ID')
                  ->references('Product_ID')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
