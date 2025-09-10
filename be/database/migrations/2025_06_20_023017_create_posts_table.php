<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('Post_ID'); // PK

            // FK -> user(ID)
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            // FK -> post_categories(Post_Categories_ID)
            $table->unsignedBigInteger('Category_ID');
            $table->foreign('Category_ID')->references('Post_Categories_ID')->on('post_categories')->onDelete('cascade');

            $table->string('Title');
            $table->string('Thumbnail')->nullable();
            $table->text('Content');
            $table->string('Excerpt')->nullable();
            $table->boolean('Status')->default(true);
            $table->unsignedInteger('View')->default(0);

            $table->timestamp('Created_at')->useCurrent();
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
