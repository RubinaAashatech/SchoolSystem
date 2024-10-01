<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use File;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/admin';

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\SuperAdmin')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'super_admin'));
                    foreach ($files as $file) {
                        // dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\DistrictAdmin')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'district_admin'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\MunicipalityAdmin')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'municipality_admin'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\LocalBodyAdmin')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'local_body_admin'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\SchoolHead')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'school_head'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\SchoolAdmin')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'school_admin'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

            Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\Shared')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'shared'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });

                Route::middleware(['web', 'auth', 'userAttributes', 'adminRoleAccess', 'adminPermissionAccess'])
                ->prefix('admin')->name('admin.')
                ->namespace($this->namespace . '\Staff')
                ->group(function () {
                    $files = File::allFiles(base_path('routes' . DIRECTORY_SEPARATOR . 'staff'));
                    foreach ($files as $file) {
                        //dd($file->getRealPath());
                        require_once $file->getRealPath();
                    }
                });
        });
    }
}