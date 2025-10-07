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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('guardian_name')->nullable()->after('relative_address');
            $table->string('guardian_mobile_no')->nullable()->after('guardian_name');
            $table->string('guardian_address')->nullable()->after('guardian_mobile_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('guardian_name');
            $table->dropColumn('guardian_mobile_no');
            $table->dropColumn('guardian_address');
        });
    }
};
