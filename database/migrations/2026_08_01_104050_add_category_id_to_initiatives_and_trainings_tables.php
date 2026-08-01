<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained('categories');
        });
    }

    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
