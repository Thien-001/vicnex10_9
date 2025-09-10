<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->bigIncrements('Courts_ID'); // PK
            $table->string('Name');
            $table->string('Location');
            $table->text('Description')->nullable();
            $table->string('Image')->nullable();
            $table->string('Court_type');
            $table->decimal('Price_per_hour', 10, 2);
            $table->boolean('Status')->default(true);
            $table->timestamp('Created_at')->nullable();
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
