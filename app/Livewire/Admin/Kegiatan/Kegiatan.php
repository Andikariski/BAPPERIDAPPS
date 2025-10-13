<?php

namespace App\Livewire\Admin\Kegiatan;

use App\Models\Bidang;
use App\Models\Kegiatan as ModelsKegiatan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Kegiatan extends Component
{
    use WithPagination;
    public $search = '';
    public $bidang_filter = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedBidangFilter()
    {
        $this->resetPage();
    }

    // Method untuk delete kegiatan
    #[On('delete-data-kegiatan')]
    public function deleteKegiatan($id)
    {
        DB::beginTransaction();

        try {
            // Hapus semua foto fisik
            $kegiatan = ModelsKegiatan::find($id);
            if ($kegiatan) {

                foreach ($kegiatan->fotoKegiatan as $foto) {
                    $foto->hapusFile();
                }

                // Hapus kegiatan (foto akan terhapus otomatis karena cascade)
                $kegiatan->delete();

                DB::commit();

                // Show success message
                $this->dispatch('success-delete-data');

                // Refresh data
                $this->resetPage();
            }
        } catch (\Exception $e) {
            DB::rollback();

            dump($e);
            $this->dispatch('failed-delete-data');
        }
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Kegiatan', 'pageTitle' => 'Kegiatan'])]
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
