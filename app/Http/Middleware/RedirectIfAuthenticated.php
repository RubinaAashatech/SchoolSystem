<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->getRoleNames()[0];
                // dd($role);

                switch ($role) {
                    case 'Super Admin':
                        return new RedirectResponse(route('admin.superAdmin.dashboard'));
                    case 'District Admin':
                        return new RedirectResponse(route('admin.district.dashboard'));
                    case 'Municipality Admin':
                        return new RedirectResponse(route('admin.municipality.dashboard'));
                    case 'Head School':
                        return new RedirectResponse(route('admin.headSchool.dashboard'));
                    case 'School Admin':
                        return new RedirectResponse(route('admin.schoolAdmin.dashboard'));
                    case 'Teacher':
                        return new RedirectResponse(route('admin.teacher.dashboard'));                        
                    case 'Accountant':
                        return new RedirectResponse(route('admin.staff.dashboard'));
                    case 'Librarian':
                        return new RedirectResponse(route('admin.staff.dashboard'));
                    case 'Principal':
                        return new RedirectResponse(route('admin.staff.dashboard'));
                    case 'Receptionist':
                        return new RedirectResponse(route('admin.staff.dashboard'));
                    case 'Student':
                        return new RedirectResponse(route('admin.student.dashboard'));
                    case 'Staff':
                        return new RedirectResponse(route('admin.staff.dashboard'));
                    default:
                        return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}