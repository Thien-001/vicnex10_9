<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSalesTable extends Migration
{
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->decimal('price_sale', 15, 2);
            $table->decimal('price_old', 15, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Sửa lại tên cột tham chiếu
            $table->foreign('product_id')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_sales');
    }
}