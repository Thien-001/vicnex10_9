<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('Notifications_ID'); // PK

            // FK -> user(ID)
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            $table->string('Title');
            $table->text('Message');
            $table->string('Type')->nullable();

            $table->timestamp('Created_at')->useCurrent(); // Bạn ghi là Created_ad, mình chỉnh lại thành Created_at cho đúng
            $table->timestamp('Updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
