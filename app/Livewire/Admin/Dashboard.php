<?php

namespace App\Livewire\Admin;

use App\Models\Berita;
use App\Models\DokumenPublik;
use App\Models\Kegiatan;
use App\Models\Pegawai;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('components.layouts.admin', ['title' => 'Admin | Dashboard', 'pageTitle' => 'Dashboard'])]
    public function render()
    {
        $totalPegawai   = Pegawai::count();
        $totalDokumen   = DokumenPublik::count();
        $totalBerita    = Berita::count();
        $totalKegiatan  = Kegiatan::count();
        return view('livewire.admin.dashboard',[
            'totalPegawai'  => $totalPegawai,
            'totalDokumen'  => $totalDokumen,
            'totalBerita'   => $totalBerita,
            'totalKegiatan' => $totalKegiatan,
        ]);
    }
}
