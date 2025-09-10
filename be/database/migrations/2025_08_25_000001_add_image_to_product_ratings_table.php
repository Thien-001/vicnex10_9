<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToProductRatingsTable extends Migration
{
    public function up()
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            $table->string('image')->nullable()->after('Rating');
        });
    }

    public function down()
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}