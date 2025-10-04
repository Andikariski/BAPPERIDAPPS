<?php

namespace App\Livewire\Kegiatan;

use App\Models\Bidang;
use App\Models\Kegiatan;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedBidang = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedBidang()
    {
        $this->resetPage();
    }


    #[Layout('components.layouts.public')]
    public function render()
    {
        $query = Kegiatan::query()
            ->with('bidang');

        if (!empty($this->search)) {
            $query->where('nama_kegiatan', 'like', "%{$this->search}%");
        }
        if (!empty($this->selectedBidang)) {
            $query->where('fkid_bidang', $this->selectedBidang);
        }

        $dataKegiatan = $query->latest()->paginate(6);

        return view('livewire.kegiatan.index', [
            'dataBidang' => Bidang::all(),
            'dataKegiatan' => $dataKegiatan
        ]);
    }
}
