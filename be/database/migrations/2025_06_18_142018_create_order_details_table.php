<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_detail_id'); // PK

            // Foreign key đến bảng orders
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');

            // Foreign key đến bảng products
            $table->unsignedBigInteger('Product_ID');
            $table->foreign('Product_ID')->references('Product_ID')->on('products')->onDelete('restrict');

            // Thông tin chi tiết đơn hàng
            $table->string('product_name');
            $table->string('SKU');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);

            // Thời gian tạo
            $table->timestamp('create_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
}
