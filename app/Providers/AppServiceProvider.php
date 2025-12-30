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
        // Prevenimos error si la tabla no existe aún
        if (Schema::hasTable('settings')) {
            $siteName = Setting::where('key', 'site_name')->value('value') ?? config('app.name');
            $siteLogo = Setting::where('key', 'site_logo')->value('value');
            $siteBanner = Setting::where('key', 'site_banner')->value('value');
            
            // --- LÓGICA DE TEMA SEGURA ---
            $themeKey = Setting::where('key', 'site_theme')->value('value') ?? 'default'; // Si no hay en BD, usa 'default'
            
            // Intentamos cargar la config
            $themes = config('themes');

            // --- BLOQUE DE SEGURIDAD (FIX) ---
            // Si config('themes') falló o no tiene la clave 'default', creamos un fallback manual.
            if (!$themes || !is_array($themes) || !isset($themes['default'])) {
                // Definimos manualmente el tema 'default' (Emerald) para evitar el error
                $themes = [
                    'default' => [
                        'colors' => [
                            '50' => '#ecfdf5', '100' => '#d1fae5', '200' => '#a7f3d0',
                            '300' => '#6ee7b7', '400' => '#34d399', '500' => '#10b981',
                            '600' => '#059669', '700' => '#047857', '800' => '#065f46',
                            '900' => '#064e3b', '950' => '#022c22',
                        ]
                    ]
                ];
                // Forzamos el uso del default seguro si el themeKey solicitado no existe
                if (!isset($themes[$themeKey])) {
                    $themeKey = 'default';
                }
            }

            // Seleccionamos el tema (ahora es seguro porque garantizamos que $themes['default'] existe)
            $selectedTheme = $themes[$themeKey] ?? $themes['default'];
            
            // Generar CSS
            $cssVariables = ":root {";
            if (isset($selectedTheme['colors']) && is_array($selectedTheme['colors'])) {
                foreach ($selectedTheme['colors'] as $shade => $value) {
                    $cssVariables .= "--brand-{$shade}: {$value};";
                }
            }
            $cssVariables .= "}";
            // --- FIN LÓGICA ---

            View::share('globalSiteName', $siteName);
            View::share('globalSiteLogo', $siteLogo);
            View::share('globalSiteBanner', $siteBanner);
            View::share('themeCss', $cssVariables);
            View::share('currentTheme', $themeKey);
            
        } else {
            // Fallback si no hay tabla settings
            View::share('globalSiteName', config('app.name'));
            View::share('globalSiteLogo', null);
            View::share('globalSiteBanner', null);
            View::share('themeCss', '');
            View::share('currentTheme', 'default');
        }
    }
}
