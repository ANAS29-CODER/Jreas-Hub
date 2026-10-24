<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('workshops', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('donation_campaigns', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('activities_log', function (Blueprint $table) {
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('workshops', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('donation_campaigns', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('activities_log', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
};
