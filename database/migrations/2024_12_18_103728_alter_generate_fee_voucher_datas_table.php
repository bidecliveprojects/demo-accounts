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
        Schema::table('generate_fee_voucher_datas', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['department_id']);
            // Drop the column itself
            $table->dropColumn('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_fee_voucher_datas', function (Blueprint $table) {
            //
        });
    }
};
