<?php

namespace App\Livewire\Admin\Kegiatan;

use App\Models\Kegiatan;
use Livewire\Attributes\Layout;
use Livewire\Component;

class KegiatanDetail extends Component
{
    public Kegiatan $kegiatan;

    public function mount(Kegiatan $kegiatan)
    {
        $this->kegiatan = $kegiatan->load('fotoKegiatan', 'bidang');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.kegiatan.kegiatan-detail');
    }
}
