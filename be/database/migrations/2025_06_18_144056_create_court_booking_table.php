<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('court_booking', function (Blueprint $table) {
            $table->bigIncrements('Court_booking_ID'); // PK

            // FK -> user(ID)
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            // FK -> courts(Courts_ID)
            $table->unsignedBigInteger('Courts_ID');
            $table->foreign('Courts_ID')->references('Courts_ID')->on('courts')->onDelete('cascade');

            $table->date('Booking_date');
            $table->time('Start_time');
            $table->time('End_time');
            $table->integer('Duration_hours');
            $table->decimal('Price_per_hour', 10, 2);
            $table->decimal('Total_price', 10, 2);
            $table->text('Note')->nullable();
            $table->boolean('Status')->default(true);
            $table->timestamp('Create_at')->useCurrent();
            $table->timestamp('Update_at')->nullable();

            // FK -> vouchers(Vouchers_ID)
            $table->unsignedBigInteger('Vouchers_ID')->nullable();
            $table->foreign('Vouchers_ID')->references('Vouchers_ID')->on('vouchers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_booking');
    }
};
