<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->string('Slug')->nullable(); // hoặc required tùy nhu cầu
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('Slug');
    });
}

};
