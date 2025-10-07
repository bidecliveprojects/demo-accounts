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
            $table->integer('assign_task_status')->default(1)->comment('1 = Publish, 2 = Draft');
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
