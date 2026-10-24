<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// The admin forms treat these fields as optional, but the columns were created NOT NULL,
// so saving a record without them failed with a database error.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->string('short_description')->nullable()->change();
            $table->string('instructor_name')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });

        Schema::table('workshops', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->string('instructor_name')->nullable()->change();
            $table->date('date')->nullable()->change();
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->string('university')->nullable()->change();
            $table->string('supervisor_name')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->string('manager_name')->nullable()->change();
            $table->date('start_date')->nullable()->change();
        });

        Schema::table('donation_campaigns', function (Blueprint $table) {
            $table->longText('description')->nullable()->change();
            $table->date('start_date')->nullable()->change();
        });

        Schema::table('activities_log', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Intentionally irreversible: re-adding NOT NULL would fail once rows contain empty values.
    }
};
