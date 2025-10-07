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
        Schema::create('additional_activity_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('additional_activity_id');
            $table->unsignedBigInteger('head_id');
            $table->unsignedBigInteger('level_of_performance_id');

            $table->foreign('additional_activity_id')
                ->references('id')
                ->on('additional_activities')
                ->onDelete('cascade');

            $table->foreign('head_id')
                ->references('id')
                ->on('heads')
                ->onDelete('cascade');
            
            $table->foreign('level_of_performance_id')
                ->references('id')
                ->on('level_of_performances')
                ->onDelete('cascade');
            
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
        Schema::dropIfExists('additional_activity_datas');
    }
};
