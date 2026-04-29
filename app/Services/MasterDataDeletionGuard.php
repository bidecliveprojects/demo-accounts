<?php

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use App\Models\States;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class MasterDataDeletionGuard
{
    /**
     * @return array{ok: bool, message: string}
     */
    public static function assertCountryDeletable(int $countryId, ?int $companyId = null): array
    {
        $companyId = $companyId ?? (int) session('company_id');
        $country = Country::where('id', $countryId)->where('company_id', $companyId)->first();
        if (! $country) {
            return ['ok' => false, 'message' => 'Country not found or does not belong to this company.'];
        }

        $hasStates = States::where('country_id', $countryId)->where('company_id', $companyId)->exists();
        if ($hasStates) {
            return ['ok' => false, 'message' => 'Cannot delete: this country has one or more states. Remove or reassign states first.'];
        }

        return ['ok' => true, 'message' => ''];
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public static function assertStateDeletable(int $stateId, ?int $companyId = null): array
    {
        $companyId = $companyId ?? (int) session('company_id');
        $state = States::where('id', $stateId)->where('company_id', $companyId)->first();
        if (! $state) {
            return ['ok' => false, 'message' => 'State not found or does not belong to this company.'];
        }

        $hasCities = City::where('state_id', $stateId)->where('company_id', $companyId)->exists();
        if ($hasCities) {
            return ['ok' => false, 'message' => 'Cannot delete: this state has one or more cities. Remove or reassign cities first.'];
        }

        return ['ok' => true, 'message' => ''];
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public static function assertCityDeletable(int $cityId, ?int $companyId = null): array
    {
        $companyId = $companyId ?? (int) session('company_id');
        $city = City::where('id', $cityId)->where('company_id', $companyId)->first();
        if (! $city) {
            return ['ok' => false, 'message' => 'City not found or does not belong to this company.'];
        }

        $refs = [];

        if (DB::table('employees')->where('company_id', $companyId)->where('city_id', $cityId)->exists()) {
            $refs[] = 'employees';
        }
        if (DB::table('customers')->where('company_id', $companyId)->where('city_id', $cityId)->exists()) {
            $refs[] = 'customers';
        }
        if (DB::table('suppliers')->where('company_id', $companyId)->where('city_id', $cityId)->exists()) {
            $refs[] = 'suppliers';
        }

        $guardianUsesCity = DB::table('student_parent_and_guardian_informations as spagi')
            ->join('students as s', 'spagi.student_id', '=', 's.id')
            ->where('spagi.city_id', $cityId)
            ->where('s.company_id', $companyId)
            ->exists();
        if ($guardianUsesCity) {
            $refs[] = 'student / guardian records';
        }

        if ($refs !== []) {
            return [
                'ok' => false,
                'message' => 'Cannot delete: this city is in use (' . implode(', ', $refs) . '). Update those records first.',
            ];
        }

        return ['ok' => true, 'message' => ''];
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public static function assertRoleDeletable(int $roleId, ?int $companyId = null, ?int $companyLocationId = null): array
    {
        $companyId = $companyId ?? (int) session('company_id');
        $q = Role::where('id', $roleId)->where('company_id', $companyId);

        if (Schema::hasColumn('roles', 'company_location_id')) {
            $loc = $companyLocationId;
            if ($loc === null) {
                $raw = session('company_location_id');
                $loc = ($raw !== null && $raw !== '' && is_numeric($raw)) ? (int) $raw : null;
            }
            if ($loc !== null && $loc > 0) {
                $q->where('company_location_id', $loc);
            }
        }

        $role = $q->first();
        if (! $role) {
            return ['ok' => false, 'message' => 'Role not found or does not belong to this company/location.'];
        }

        $assignedToUsers = DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->where('model_type', User::class)
            ->exists();
        if ($assignedToUsers) {
            return ['ok' => false, 'message' => 'Cannot delete: this role is assigned to one or more users. Remove assignments first.'];
        }

        return ['ok' => true, 'message' => ''];
    }
}
