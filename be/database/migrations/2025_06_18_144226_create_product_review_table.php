<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_review', function (Blueprint $table) {
            $table->bigIncrements('Product_review_ID'); // PK

            // FK -> products(Product_ID)
            $table->unsignedBigInteger('Product_ID');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('cascade');

            // FK -> user(ID)
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            // FK -> orders(Order_ID)
            $table->unsignedBigInteger('Order_ID');
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('cascade');

            $table->unsignedTinyInteger('Rating'); // Ví dụ: 1-5
            $table->text('Comment')->nullable();
            $table->string('Image')->nullable();
            $table->boolean('Status')->default(true);
            $table->timestamp('Created_at')->useCurrent();
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_review');
    }
};
