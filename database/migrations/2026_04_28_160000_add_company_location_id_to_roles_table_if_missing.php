<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('roles') || Schema::hasColumn('roles', 'company_location_id')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('company_location_id')->nullable()->after('company_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'company_location_id')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('company_location_id');
            });
        }
    }
};
