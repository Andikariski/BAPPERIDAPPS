<?php

namespace App\Livewire\Admin\Kegiatan;

use App\Models\Bidang;
use App\Models\Kegiatan as ModelsKegiatan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Kegiatan extends Component
{
    use WithPagination;
    public $search = '';
    public $bidang_filter = '';
    // Untuk konfirmasi delete
    public $showDeleteModal = false;
    public $kegiatanToDelete = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedBidangFilter()
    {
        $this->resetPage();
    }

    // Method untuk konfirmasi delete
    public function confirmDelete($kegiatanId)
    {
        $this->kegiatanToDelete = ModelsKegiatan::find($kegiatanId);
        $this->showDeleteModal = true;
    }

    // Method untuk membatalkan delete
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->kegiatanToDelete = null;
    }

    // Method untuk delete kegiatan
    public function deleteKegiatan()
    {
        if (!$this->kegiatanToDelete) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Kegiatan tidak ditemukan'
            ]);
            return;
        }

        DB::beginTransaction();

        try {
            // Hapus semua foto fisik
            foreach ($this->kegiatanToDelete->fotoKegiatan as $foto) {
                $foto->hapusFile();
            }

            $namaKegiatan = $this->kegiatanToDelete->nama_kegiatan;

            // Hapus kegiatan (foto akan terhapus otomatis karena cascade)
            $this->kegiatanToDelete->delete();

            DB::commit();

            // Reset modal
            $this->showDeleteModal = false;
            $this->kegiatanToDelete = null;

            // Show success message
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => "Kegiatan '{$namaKegiatan}' berhasil dihapus"
            ]);

            // Refresh data
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollback();

            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Gagal menghapus kegiatan: ' . $e->getMessage()
            ]);

            // Reset modal state
            $this->showDeleteModal = false;
            $this->kegiatanToDelete = null;
        }
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $dataKegiatan = ModelsKegiatan::with(['bidang', 'fotoKegiatan'])
            ->when($this->search, function ($query) {
                $query->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                    ->orWhere('deskripsi_kegiatan', 'like', '%' . $this->search . '%');
            })
            ->when($this->bidang_filter, function ($query) {
                $query->where('fkid_bidang', $this->bidang_filter);
            })
            ->latest()
            ->paginate(8);

        $bidangList = Bidang::orderBy('nama_bidang')->get();

        return view('livewire.admin.kegiatan.kegiatan', [
            'dataKegiatan' => $dataKegiatan,
            'bidangList' => $bidangList
        ]);
    }
}
