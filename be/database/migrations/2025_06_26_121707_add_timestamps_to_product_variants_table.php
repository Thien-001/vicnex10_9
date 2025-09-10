<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function down(): void
{
    Schema::table('product_variants', function (Blueprint $table) {
        if (Schema::hasColumn('product_variants', 'created_at')) {
            $table->dropColumn('created_at');
        }
        if (Schema::hasColumn('product_variants', 'updated_at')) {
            $table->dropColumn('updated_at');
        }
    });
}


};
