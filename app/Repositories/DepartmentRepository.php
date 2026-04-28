<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DepartmentRepositoryInterface;
use App\Models\Department;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Session;
use Auth;
use DB;

class DepartmentRepository implements DepartmentRepositoryInterface
{

    public function allDepartments($data)
    {
        $status = $data['filterStatus'];
        return Department::select(
            'departments.*'
        )
        ->when($status != '', function ($q) use ($status){
            return $q->where('departments.status','=',$status);
        })
        ->where('departments.company_id', Session::get('company_id'))
        ->where('departments.company_location_id', Session::get('company_location_id'))
        ->get();
    }

    public function storeDepartment($data)
    {
        $data['company_id'] = Session::get('company_id');
        $data['status'] = 1;
        $data['created_by'] = Auth::user()->name;
        $data['created_date'] = date('Y-m-d');
        $data['company_location_id'] = Session::get('company_location_id');
        return Department::insert($data);
    }

    public function findDepartment($id)
    {
        return Department::where('id', $id)
            ->where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->first();
    }

    public function updateDepartment($data, $id)
    {
        Department::where('id', $id)
            ->where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->update($data);
    }

    public function changeDepartmentStatus($id,$status)
    {
        Department::where('id', $id)
            ->where('company_id', Session::get('company_id'))
            ->where('company_location_id', Session::get('company_location_id'))
            ->update(['status' => $status]);
    }

    /**
     * Count rows referencing this department (employees, teachers, etc.).
     */
    public function countDepartmentUsage(int $departmentId): int
    {
        $total = 0;
        $tables = [
            ['employees', 'department_id'],
            ['teachers', 'department_id'],
            ['students', 'department_id'],
            ['subjects', 'department_id'],
        ];
        foreach ($tables as [$table, $column]) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                $total += DB::table($table)->where($column, $departmentId)->count();
            }
        }

        return $total;
    }
}
