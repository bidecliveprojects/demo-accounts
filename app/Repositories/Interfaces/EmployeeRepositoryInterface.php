<?php
namespace App\Repositories\Interfaces;

Interface EmployeeRepositoryInterface{

    /**
     * @param  array<string, mixed>  $data
     */
    public function allEmployees(array $data, ?int $companyId = null, ?int $companyLocationId = null);
    public function storeEmployee($data);
    public function findEmployee($id);
    public function updateEmployee($data, $id);
    public function changeEmployeeStatus($id,$status);
}
