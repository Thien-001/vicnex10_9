<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('Slug')->unique()->after('Category_ID');
            $table->string('Meta_Title')->nullable()->after('Excerpt');
            $table->string('Meta_Description')->nullable()->after('Meta_Title');
            $table->boolean('Is_Featured')->default(false)->after('View');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['Slug', 'Meta_Title', 'Meta_Description', 'Is_Featured']);
        });
    }
};
