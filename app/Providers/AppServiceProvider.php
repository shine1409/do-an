<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Fortify;
use App\Models\Voucher;
use App\Observers\VoucherObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse
        {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });

        $this->app->bind('role', function () {
        return new \App\Services\RoleService();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Voucher::observe(\App\Observers\VoucherObserver::class);

        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
            $view->with('username', Auth::check() ? Auth::user()->name : null);
        });
    }
}
