<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\MainMenuTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // public $companyId = Session::get('company_id');
    // public function __construct(){
    //     $this->companyId = Session::get('company_id');
    // }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createMainMenuTitleForm()
    {
        $menuType = array(
            '1' => 'User',
            '2' => 'Purchase',
            '3' => 'Sales',
            '4' => 'Store',
            '5' => 'Finance',
            '6' => 'Setting',
            '7' => 'Reports',
            '8' => 'Dashboard',
            '9' => 'HR'
        );
        return view('Users.createMainMenuTitleForm', compact('menuType'));
    }

    public function createSubMenuForm()
    {
        $MainMenuTitles = new MainMenuTitle;
        $menus = DB::table('menus')->where('status', '=', '1')->get();
        return view('Users.createSubMenuForm', compact('menus'));
    }

    public function viewUsersLoginTimePeriodList()
    {
        return view('Users.viewUsersLoginTimePeriodList');
    }

    public function addUsersLoginTimePeriod()
    {
        $userList = DB::Connection('mysql')->table('users')->where('status', '=', '1')->where('company_id', '=', Session::get('company_id'))->get();
        $companyId = Session::get('company_id');
        $roles = $companyId
            ? Role::query()->where('company_id', $companyId)->where('status', 1)->orderBy('name')->get()
            : collect();

        return view('Users.addUsersLoginTimePeriod', compact('userList', 'roles'));
    }
}
