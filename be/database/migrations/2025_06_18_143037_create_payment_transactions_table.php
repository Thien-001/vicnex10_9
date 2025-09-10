<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id('Payment_ID');

            // Đúng theo bảng user
            $table->unsignedBigInteger('User_ID');
            $table->foreign('User_ID')->references('ID')->on('user')->onDelete('cascade');

            // Các cột còn lại
            $table->unsignedBigInteger('Order_ID')->nullable();
            $table->unsignedBigInteger('Court_booking_ID')->nullable();

            $table->decimal('Amount', 10, 2);
            $table->string('Method');
            $table->string('Status');
            $table->string('Transaction_code')->unique();
            $table->timestamp('Paid_at')->nullable();

            // Các khóa ngoại khác (giả định bạn có bảng orders và court_bookings)
            $table->foreign('Order_ID')->references('Order_ID')->on('orders')->onDelete('set null');
            // $table->foreign('Court_booking_ID')->references('Court_booking_ID')->on('court_bookings')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
}
