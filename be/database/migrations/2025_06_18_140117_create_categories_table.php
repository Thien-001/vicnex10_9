<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('Categories_ID');
            $table->string('Name');
            $table->text('Description')->nullable();
            $table->string('Image')->nullable();
            $table->timestamp('Create_at')->nullable();
            $table->timestamp('Update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
