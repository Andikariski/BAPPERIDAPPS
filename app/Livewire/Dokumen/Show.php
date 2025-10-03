<?php

namespace App\Livewire\Dokumen;

use App\Models\DokumenPublik;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public $dokumen;
    public function mount($id)
    {
        $this->dokumen = DokumenPublik::with('bidang')
            ->where('id', $id)
            ->firstOrFail();
    }
    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.dokumen.show');
    }
}
