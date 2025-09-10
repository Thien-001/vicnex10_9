<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('Product_ID'); // PK
            $table->unsignedBigInteger('Categories_ID'); // FK -> categories
            $table->string('Name');
            $table->string('SKU')->unique();
            $table->string('Brand')->nullable();
            $table->text('Description')->nullable();
            $table->string('Image')->nullable();
            $table->decimal('Price', 10, 2);
            $table->decimal('Discount_price', 10, 2)->nullable();
            $table->integer('Quantity')->default(0);
            $table->boolean('Status')->default(true);
            $table->timestamp('Created_at')->nullable();
            $table->timestamp('Update_at')->nullable();

        //     Foreign key
        //    $table->foreign('Categories_ID')->references('Categories_ID')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
