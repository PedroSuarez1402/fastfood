<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;


class Appearance extends Component
{
    use WithFileUploads;

    public $site_name;
    public $site_logo;
    public $current_logo;
    public $site_banner;
    public $current_banner;
    public $site_theme;

    public function mount()
    {
        $this->site_name = Setting::where('key', 'site_name')->value('value') ?? config('app.name');
        $this->current_logo = Setting::where('key', 'site_logo')->value('value');
        $this->current_banner = Setting::where('key', 'site_banner')->value('value');
        $this->site_theme = Setting::where('key', 'site_theme')->value('value') ?? 'default';
    }

    public function updatedSiteLogo()
    {
        $this->validate([
            'site_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }
    public function updatedSiteBanner()
    {
        $this->validate([
            'site_banner' => 'image', // 2MB Max para banners
        ]);
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:50',
            'site_logo' => 'nullable|image|max:2048',
            'site_banner' => 'nullable|image',
        ]);

        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $this->site_name]
        );

        // 2. Guardar Logo (Tu código existente...)
        if ($this->site_logo) {
            if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
                Storage::disk('public')->delete($this->current_logo);
            }
            $path = $this->site_logo->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
            $this->current_logo = $path;
            $this->reset('site_logo');
        }

        // 3. NUEVO: Guardar Banner
        if ($this->site_banner) {
            // Borrar banner anterior
            if ($this->current_banner && Storage::disk('public')->exists($this->current_banner)) {
                Storage::disk('public')->delete($this->current_banner);
            }
            // Guardar nuevo en carpeta 'banners'
            $pathBanner = $this->site_banner->store('banners', 'public');

            Setting::updateOrCreate(['key' => 'site_banner'], ['value' => $pathBanner]);

            $this->current_banner = $pathBanner;
            $this->reset('site_banner');
        }

        // 4. Guardar Tema
        Setting::updateOrCreate(
            ['key' => 'site_theme'], 
            ['value' => $this->site_theme]
        );

        // 1. Mensaje Flash (Movido fuera del IF para que salga siempre)
        session()->flash('success', 'Configuración de apariencia actualizada.');

        // 2. Redirección Corregida
        // Usamos el helper global request() para obtener el header, no como método de redirect()
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
