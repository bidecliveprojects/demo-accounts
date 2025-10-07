<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW student_performance_para_wise_view AS
            SELECT s.id as student_id,s.school_id,s.registration_no,s.student_name,s.date_of_admission,s.fees,p.para_name,p.id as paraId,pod.total_lines_in_para,
            pod.estimated_completion_days,pod.excelent,pod.good,pod.average,d.department_name,
            (select count(*) from student_day_wise_performances as sdwp WHERE sdwp.student_id = scp.student_id and sdwp.para_id = scp.para_id and scp.school_id = s.school_id) as countDays 
            FROM student_current_paras as scp INNER JOIN paras as p on p.id = scp.para_id INNER JOIN students as s on scp.student_id = s.id 
            INNER JOIN para_other_details as pod on pod.para_id = p.id INNER JOIN departments as d on s.department_id = d.id where scp.para_status = 2;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS student_performance_para_wise_view;");
    }
};
