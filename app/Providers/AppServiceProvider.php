<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL/MariaDB "Specified key was too long" error
        // on older database engines (WAMP, XAMPP, etc.)
        Schema::defaultStringLength(191);

        $this->registerGates();
        $this->shareSiteSetting();
    }

    /**
     * Authorization rules driven by the user's role — no hardcoded
     * role checks scattered across controllers/middleware.
     */
    protected function registerGates(): void
    {
        Gate::define('manage-users', fn (User $user): bool => $user->isAdmin());

        Gate::define('manage-settings', fn (User $user): bool => $user->isAdmin());
    }

    /**
     * Make the site-wide settings available to every view (header/footer/SEO)
     * without each controller having to load and pass them manually.
     */
    protected function shareSiteSetting(): void
    {
        View::composer('*', function ($view) {
            $setting = null;

            // Guard against the table not existing yet (e.g. before the
            // first migration run) so the app never crashes on bootstrap.
            try {
                if (Schema::hasTable('site_settings')) {
                    $setting = SiteSetting::first();
                }
            } catch (\Throwable) {
                $setting = null;
            }

            $view->with('siteSetting', $setting);
        });
    }
}
