<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('ID'); // PK
            $table->unsignedBigInteger('Role_ID'); // FK -> roles
            $table->string('Name');
            $table->string('Email')->unique();
            $table->string('Password');
            $table->string('Phone')->nullable();
            $table->enum('Gender', ['male', 'female'])->nullable();
            $table->date('Date_of_birth')->nullable();
            $table->string('Avatar')->nullable();
            $table->boolean('Status')->default(true);
            $table->string('Address')->nullable();
            $table->timestamp('Created_at')->nullable();
            $table->timestamp('Updated_at')->nullable();

            // Foreign key
            $table->foreign('Role_ID')->references('Role_ID')->on('roles')->onDelete('cascade');
        });
        }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
