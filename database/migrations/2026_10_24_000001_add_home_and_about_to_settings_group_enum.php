<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->setGroups(['general', 'social', 'contact', 'seo', 'donation', 'home', 'about']);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('group', ['home', 'about'])->delete();

        $this->setGroups(['general', 'social', 'contact', 'seo', 'donation']);
    }

    private function setGroups(array $groups): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE settings MODIFY `group` ENUM('".implode("', '", $groups)."') NOT NULL");

            return;
        }

        Schema::table('settings', function (Blueprint $table) use ($groups) {
            $table->enum('group', $groups)->change();
        });
    }
};
