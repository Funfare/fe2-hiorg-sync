<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ...
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Facades\View::composer('layouts.master', function (View $view) {
            $impersonate = \Session::get('impersonate_admin');
            if($impersonate !== null) {
                $impersonate = User::find($impersonate);
            }
            $view->with('impersonate', $impersonate);
        });
    }
}
