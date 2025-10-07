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
            $table->integer('fee_voucher_status')
                ->default(1)
                ->comment('1 = Unpaid, 2 = Paid')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_fee_voucher_datas', function (Blueprint $table) {
            $table->integer('fee_voucher_status')->change(); // Revert changes if needed
        });
    }
};
