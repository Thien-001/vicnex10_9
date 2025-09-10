<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->bigIncrements('Attributes_ID'); // PK
            $table->string('Name');
            $table->text('Description')->nullable();
            $table->timestamp('Create_at')->useCurrent();
            $table->timestamp('Update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
