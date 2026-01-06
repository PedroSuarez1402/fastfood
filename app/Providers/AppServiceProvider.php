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
        if (Schema::hasTable('settings')) {
            $siteName = Setting::where('key', 'site_name')->value('value') ?? config('app.name');
            $siteLogo = Setting::where('key', 'site_logo')->value('value');
            $siteBanner = Setting::where('key', 'site_banner')->value('value');
            
            // 1. Obtener tema de la BD
            $themeKey = Setting::where('key', 'site_theme')->value('value') ?? 'default';
            
            // 2. Cargar configuración
            $themes = config('themes');

            // 3. Selección segura del tema
            // Si el tema guardado existe, lo usamos. Si no, usamos 'default'.
            // Y si 'default' no existe (por error de config), usamos el primer tema disponible.
            if (isset($themes[$themeKey])) {
                $selectedTheme = $themes[$themeKey];
            } elseif (isset($themes['default'])) {
                $selectedTheme = $themes['default'];
            } else {
                // Fallback de emergencia si el archivo themes.php está roto
                $selectedTheme = [
                    'colors' => [
                        '50' => '#ecfdf5', '300' => '#6ee7b7', '600' => '#059669', '900' => '#064e3b', '950' => '#022c22' // Emerald
                    ]
                ];
            }

            // 4. Generar CSS
            $cssVariables = "html {"; // Usamos html para mayor especificidad
            if (isset($selectedTheme['colors'])) {
                foreach ($selectedTheme['colors'] as $shade => $value) {
                    $cssVariables .= "--brand-{$shade}: {$value};";
                    $cssVariables .= "--color-brand-{$shade}: {$value};";
                    // Sobrescribimos Zinc, Neutral y Gray para colorear toda la app
                    $cssVariables .= "--color-zinc-{$shade}: {$value};";
                    $cssVariables .= "--color-neutral-{$shade}: {$value};";
                    $cssVariables .= "--color-gray-{$shade}: {$value};";
                }
            }
            $cssVariables .= "}";

            View::share('globalSiteName', $siteName);
            View::share('globalSiteLogo', $siteLogo);
            View::share('globalSiteBanner', $siteBanner);
            View::share('themeCss', $cssVariables);
            View::share('currentTheme', $themeKey);
            
        } else {
            // Fallback sin BD
            View::share('globalSiteName', config('app.name'));
            View::share('globalSiteLogo', null);
            View::share('globalSiteBanner', null);
            View::share('themeCss', '');
            View::share('currentTheme', 'default');
        }
    }
}
