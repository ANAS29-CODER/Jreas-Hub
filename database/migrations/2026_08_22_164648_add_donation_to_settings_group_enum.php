<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setGroups(['general', 'social', 'contact', 'seo', 'donation']);
    }

    public function down(): void
    {
        $this->setGroups(['general', 'social', 'contact', 'seo']);
    }

    private function setGroups(array $groups): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE settings MODIFY `group` ENUM('".implode("', '", $groups)."') NOT NULL");

            return;
        }

        // SQLite (used by the test suite) can't MODIFY a column, so let Laravel rebuild it.
        Schema::table('settings', function (Blueprint $table) use ($groups) {
            $table->enum('group', $groups)->change();
        });
    }
};
