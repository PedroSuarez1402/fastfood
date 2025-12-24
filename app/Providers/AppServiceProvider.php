<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Importante
use Illuminate\Support\Facades\Schema; // Importante
use App\Models\Setting; // Importante

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
        // Prevenimos error si la tabla no existe aún (ej. al correr migraciones frescas)
        if (Schema::hasTable('settings')) {
            // Obtenemos configuración o usamos valores por defecto
            $siteName = Setting::where('key', 'site_name')->value('value') ?? config('app.name');
            $siteLogo = Setting::where('key', 'site_logo')->value('value');
            $siteBanner = Setting::where('key', 'site_banner')->value('value');
            
            // Compartimos las variables con TODAS las vistas
            View::share('globalSiteName', $siteName);
            View::share('globalSiteLogo', $siteLogo);
            View::share('globalSiteBanner', $siteBanner);
        } else {
            View::share('globalSiteName', config('app.name'));
            View::share('globalSiteLogo', null);
            View::share('globalSiteBanner', null);
        }
    }
}
