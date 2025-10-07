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
        Schema::table('generate_fee_vouchers', function (Blueprint $table) {
            $table->integer('class_id')->default(0)->after('section_id');
            $table->integer('jv_id')->default(0)->after('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generate_fee_vouchers', function (Blueprint $table) {
            //
        });
    }
};
