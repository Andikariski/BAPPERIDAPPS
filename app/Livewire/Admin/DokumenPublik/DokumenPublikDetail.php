<?php

namespace App\Livewire\Admin\DokumenPublik;

use App\Models\DokumenPublik;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DokumenPublikDetail extends Component
{
    public $dokumen;
    public $showViewer = true;

    public function mount($id)
    {
        $this->dokumen = DokumenPublik::with('bidang')->findOrFail($id);
    }

    public function download()
    {
        // Pastikan menggunakan disk 'public'
        return Storage::disk('public')->download(
            $this->dokumen->file_path,
            $this->dokumen->file_name
        );
    }

    public function toggleViewer()
    {
        $this->showViewer = !$this->showViewer;
    }

    // Helper method untuk cek tipe file
    public function isViewable()
    {
        $viewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($this->dokumen->file_type), $viewableTypes);
    }
    #[Layout('components.layouts.admin', ['title' => 'Admin | Dokumen Publik', 'pageTitle' => 'Dokumen Publik'])]
    public function render()
    {
        return view('livewire.admin.dokumen-publik.dokumen-publik-detail');
    }
}
