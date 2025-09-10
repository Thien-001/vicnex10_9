<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_values', function (Blueprint $table) {
            $table->bigIncrements('Values_ID'); // PK

            // FK -> product_attributes(Attributes_ID)
            $table->unsignedBigInteger('Attributes_ID');
            $table->foreign('Attributes_ID')->references('Attributes_ID')->on('product_attributes')->onDelete('cascade');

            $table->string('Value');
            $table->timestamp('Create_at')->useCurrent();
            $table->timestamp('Update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_values');
    }
};
