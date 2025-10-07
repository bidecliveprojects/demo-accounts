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
        Schema::create('para_other_details', function (Blueprint $table) {
            $table->id();
            $table->integer('para_id');
            $table->integer('total_lines_in_para');
            $table->integer('estimated_completion_days');
            $table->integer('excelent');
            $table->integer('good');
            $table->integer('average');
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('para_other_details');
    }
};
