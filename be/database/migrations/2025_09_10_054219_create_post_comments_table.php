<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->bigIncrements('Comment_ID');
            $table->unsignedBigInteger('Post_ID');
            $table->unsignedBigInteger('User_ID');
            $table->text('text');
            $table->timestamps();

            $table->foreign('Post_ID')->references('Post_ID')->on('posts')->onDelete('cascade');
            $table->foreign('User_ID')->references('ID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
