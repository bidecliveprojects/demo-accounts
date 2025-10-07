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
            $table->integer('grace_time')->default(0)->after('login_access');
            $table->string('start_time')->after('grace_time');
            $table->string('end_time')->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('grace_time');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
};
