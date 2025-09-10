<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->bigIncrements('Vouchers_ID'); // PK

            $table->string('Code')->unique();
            $table->enum('Discount_type', ['percentage', 'fixed']);
            $table->decimal('Discount_value', 10, 2);
            $table->integer('Max_uses')->nullable();
            $table->date('Expires')->nullable();
            $table->string('applies_to')->nullable(); // Mô tả đối tượng áp dụng (user, order, booking,...)
            $table->timestamp('Paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
