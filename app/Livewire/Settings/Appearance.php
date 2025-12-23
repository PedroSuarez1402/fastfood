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

    public function mount()
    {
        $this->site_name = Setting::where('key', 'site_name')->value('value') ?? config('app.name');
        $this->current_logo = Setting::where('key', 'site_logo')->value('value');
    }

    public function updatedSiteLogo()
    {
        $this->validate([
            'site_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:50',
            'site_logo' => 'nullable|image|max:1024',
        ]);

        Setting::updateOrCreate(
            ['key' => 'site_name'],
            ['value' => $this->site_name]
        );

        if ($this->site_logo){
            if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
                Storage::disk('public')->delete($this->current_logo);
            }

            $path = $this->site_logo->store('logos', 'public');

            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                ['value' => $path]
            );

            $this->current_logo = $path;
            $this->reset('site_logo');

            session()->flash('success', 'Configuración de apariencia actualizada.');

            return redirect()->request()->header('Referer');
        }
    }
    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
