<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigIncrements('Favorite_ID');
            $table->unsignedBigInteger('User_ID');
            $table->unsignedBigInteger('Products_ID');

            // Foreign keys
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');
            $table->foreign('Products_ID')->references('Product_ID')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('favorites');
    }
};
