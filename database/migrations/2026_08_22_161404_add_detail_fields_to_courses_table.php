<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->text('what_you_learn')->nullable()->after('description');
            $table->text('curriculum')->nullable()->after('what_you_learn');
            $table->unsignedTinyInteger('rating')->nullable()->after('curriculum');
            $table->unsignedInteger('reviews_count')->default(0)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['what_you_learn', 'curriculum', 'rating', 'reviews_count']);
        });
    }
};
