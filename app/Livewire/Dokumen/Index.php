<?php

namespace App\Livewire\Dokumen;

use App\Models\Bidang;
use App\Models\DokumenPublik;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searchDokumen = '';
    public $filterBidang = '';

    public function updatedSearchBidang()
    {
        $this->resetPage();
    }
    public function updatedFilterBidang()
    {
        $this->resetPage();
    }

    public function download($filePath, $fileName)
    {
        return Storage::disk('public')->download($filePath, $fileName);
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        $dataDokumen = DokumenPublik::query()
            ->with('bidang')
            ->when($this->searchDokumen, function ($q) {
                $q->where('nama_dokumen', 'like', "%{$this->searchDokumen}%");
            })
            ->when($this->filterBidang, function ($q) {
                $q->where('fkid_bidang', $this->filterBidang);
            })
            ->latest()
            ->paginate(5);

        $dataBidang = Bidang::all();

        return view('livewire.dokumen.index', [
            'dataDokumen' => $dataDokumen,
            'dataBidang' => $dataBidang
        ]);
    }
}
