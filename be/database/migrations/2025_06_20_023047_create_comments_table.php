<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('Comment_ID'); // PK

            // FK -> posts(Post_ID)
            $table->unsignedBigInteger('Post_ID');
            $table->foreign('Post_ID')->references('Post_ID')->on('posts')->onDelete('cascade');

            // FK -> user(ID)
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            $table->text('Content');
            $table->boolean('Status')->default(true);
            $table->timestamp('Create_at')->useCurrent();
            $table->timestamp('Update_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
