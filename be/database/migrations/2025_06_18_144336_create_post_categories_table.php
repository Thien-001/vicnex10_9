<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->bigIncrements('Post_Categories_ID'); // PK
            $table->string('Title');
            $table->text('Content')->nullable();
            $table->boolean('Status')->default(true);
            $table->unsignedBigInteger('View')->default(0);
            $table->timestamp('Created_at')->useCurrent();
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_categories');
    }
};
