<?php

namespace App\Livewire\Admin\DokumenPublik;

use App\Models\Bidang;
use App\Models\DokumenPublik as ModelsDokumenPublik;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DokumenPublik extends Component
{
    use WithPagination;

    public $search = '';
    public $filterBidang = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterBidang' => ['except' => '']
    ];

    public function download()
    {
        // Pastikan menggunakan disk 'public'
        return Storage::disk('public')->download(
            $this->dokumen->file_path,
            $this->dokumen->file_name
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterBidang()
    {
        $this->resetPage();
    }

    #[On('delete-data-dokumen')]
    public function delete($id)
    {
        $dokumen = ModelsDokumenPublik::find($id);

        try {
            if ($dokumen) {
                if (Storage::disk('public')->exists($dokumen->file_path)) {
                    Storage::disk('public')->delete($dokumen->file_path);
                }

                if ($dokumen->thumbnail_path && Storage::disk('public')->exists($dokumen->thumbnail_path)) {
                    Storage::disk('public')->delete($dokumen->thumbnail_path);
                }

                $dokumen->delete();
            }
            $this->dispatch('success-delete-data');
        } catch (Exception $e) {
            $this->dispatch('failed-delete-data');
        }
    }


    #[Layout('components.layouts.admin', ['title' => 'Admin | Dokumen Publik', 'pageTitle' => 'Dokumen Publik'])]
    public function render()
    {
        $dataDokumen = ModelsDokumenPublik::with('bidang')
            ->when($this->search, function ($query) {
                $query->where('nama_dokumen', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi_dokumen', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterBidang, function ($query) {
                $query->where('fkid_bidang', $this->filterBidang);
            })
            ->latest()
            ->paginate(12);

        $dataBidang = Bidang::all();

        return view(
            'livewire.admin.dokumen-publik.dokumen-publik',
            [
                'dataDokumen' => $dataDokumen,
                'dataBidang' => $dataBidang
            ]
        );
    }
}
