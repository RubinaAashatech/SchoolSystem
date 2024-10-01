<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        $role = Auth::user()->getRoleNames()[0];
        switch ($role) {
            case 'Super Admin':
                return route('admin.superAdmin.dashboard');
            case 'District Admin':
                return route('admin.district.dashboard');
            case 'Municipality Admin':
                return route('admin.municipality.dashboard');
            case 'Head School':
                return route('admin.headSchool.dashboard');
            case 'School Admin':
                return route('admin.schoolAdmin.dashboard');
            case 'Teacher':
                return route('admin.teacher.dashboard');
            case 'Accountant':
                return route('admin.staff.dashboard');
            case 'Librarian':
                return route('admin.staff.dashboard');
            case 'Principal':
                return route('admin.staff.dashboard');
            case 'Receptionist':
                return route('admin.staff.dashboard');
            case 'Student':
                return route('admin.student.dashboard');

            default:
                return '/home';
                break;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}