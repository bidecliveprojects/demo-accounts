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
        Schema::table('assign_tasks', function (Blueprint $table) {
            $table->integer('school_id')->after('id');
            $table->integer('school_campus_id')->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assign_tasks', function (Blueprint $table) {
            //
        });
    }
};
