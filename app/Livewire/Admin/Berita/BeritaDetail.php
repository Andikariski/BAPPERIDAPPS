<?php

namespace App\Livewire\Admin\Berita;

use App\Models\Berita;
use Livewire\Attributes\Layout;
use Livewire\Component;

class BeritaDetail extends Component
{
    public Berita $berita;

    public function mount(Berita $berita)
    {
        $this->berita =  $berita->load('bidang', 'tags');
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Detail Berita', 'pageTitle' => 'Detail Berita'])]
    public function render()
    {
        return view('livewire.admin.berita.berita-detail');
    }
}
