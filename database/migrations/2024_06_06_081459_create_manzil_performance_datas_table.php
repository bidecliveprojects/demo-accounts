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
        Schema::create('manzil_performance_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manzil_performance_id');
            $table->unsignedBigInteger('para_id');
            $table->unsignedBigInteger('level_of_performance_id');
            $table->foreign('manzil_performance_id')
                ->references('id')
                ->on('manzil_performances')
                ->onDelete('cascade');

            $table->foreign('para_id')
                ->references('id')
                ->on('paras')
                ->onDelete('cascade');
            
            $table->foreign('level_of_performance_id')
                ->references('id')
                ->on('level_of_performances')
                ->onDelete('cascade');
            
            $table->integer('status')->default(1)->comment('1 = Active, 2 = Inactive');
            $table->string('created_by');
            $table->date('created_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manzil_performance_datas');
    }
};
