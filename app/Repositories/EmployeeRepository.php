<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmployeeRepository implements EmployeeRepositoryInterface
{

    public function allEmployees(array $data, ?int $companyId = null, ?int $companyLocationId = null)
    {
        $status = $data['filterStatus'] ?? '';
        $empType = $data['filterEmpType'] ?? '';
        $departments = $data['filterDepartments'] ?? '';

        $cid = $companyId ?? Session::get('company_id');
        $lid = $companyLocationId ?? Session::get('company_location_id');

        $cid = is_numeric($cid) ? (int) $cid : 0;
        $lid = is_numeric($lid) ? (int) $lid : 0;

        if ($cid < 1 || $lid < 1) {
            return collect();
        }

        return DB::table('employees as e')
            ->select('e.*')
            ->when($status !== '', function ($q) use ($status) {
                return $q->where('e.status', '=', $status);
            })
            ->when($empType !== '', function ($q) use ($empType) {
                return $q->where('e.emp_type', '=', $empType);
            })
            ->when($departments !== '', function ($q) use ($departments) {
                return $q->where('e.department_id', '=', $departments);
            })
            ->where('e.company_id', $cid)
            ->where('e.company_location_id', $lid)
            ->orderBy('e.id', 'ASC')
            ->get();
    }

    public function storeEmployee($data)
    {
        return Employee::create($data);
        
    }

    public function findEmployee($id)
    {
        return Employee::find($id);
    }

    public function updateEmployee($data, $id)
    {
        return DB::table('employees')->where('id', $id)->update($data);
    }

    public function changeEmployeeStatus($id,$status)
    {
        $employee = Employee::where('id',$id)->update(['status' => $status]);
    }
}
