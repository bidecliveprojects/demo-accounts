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
            $table->integer('job_type')->default(1)->comment('1 = Full Time, 2 = Part Time')->after('maritarial_status');
            $table->integer('employment_status')->default(1)->comment('1 = Permanent, 2 = Contract Base')->after('job_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('job_type');
            $table->dropColumn('employment_status');
        });
    }
};
