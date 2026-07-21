<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Share sidebar menus with all admin views based on logged-in user's role
        View::composer('admin.*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $sidebarMenus = $user->getSidebarMenus();
                $view->with('sidebarMenus', $sidebarMenus);
            } else {
                $view->with('sidebarMenus', collect());
            }
        });
    }
}
