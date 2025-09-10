<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('Created_at', 'created_at');
            $table->renameColumn('Update_at', 'updated_at');
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('created_at', 'Created_at');
            $table->renameColumn('updated_at', 'Update_at');
        });
    }
};
