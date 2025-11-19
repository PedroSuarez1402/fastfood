<?php

namespace App\Livewire\Admin\Mesas;

use App\Models\Mesa;
use Livewire\Component;

class Index extends Component
{
    public $mesas;

    public function mount()
    {
        $this->mesas = Mesa::orderBy('id', 'desc')->get();
    }
    public function render()
    {
        return view('livewire.admin.mesas.index');
    }
}
