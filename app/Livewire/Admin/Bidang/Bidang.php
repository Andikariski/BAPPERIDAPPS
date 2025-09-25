<?php

namespace App\Livewire\Admin\Bidang;

use App\Models\Bidang as ModelsBidang;
use Exception;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Bidang extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255')]
    public $namaBidang = '';

    public $editingId = null;
    public $bidangId = null;
    public $isEdit = false;

    // Modal state
    public $showModal = false;
    public $modalTitle = '';

    public $searchBidang;

    protected $messages = [
        'namaBidang.required' => 'Nama bidang harus diisi.',
        'namaBidang.max' => 'Nama bidang maksimal 255 karakter.'
    ];

    public function openTambahModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->modalTitle = 'Tambah Bidang Baru';
        $this->showModal = true;
    }

    public function openEditModal($bidangId)
    {
        $bidang = ModelsBidang::find($bidangId);

        if ($bidang) {
            $this->bidangId = $bidang->id;
            $this->namaBidang = $bidang->nama_bidang;
            $this->isEdit = true;
            $this->modalTitle = 'Edit Bidang';
            $this->showModal = true;
        }
    }

    public function simpan()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                // Update data
                $bidang = ModelsBidang::find($this->bidangId);
                $bidang->update([
                    'nama_bidang' => $this->namaBidang
                ]);

                session()->flash('message', 'Bidang berhasil diperbarui!');
            } else {
                // Tambah data baru
                ModelsBidang::create([
                    'nama_bidang' => $this->namaBidang
                ]);

                session()->flash('message', 'Bidang berhasil ditambahkan!');
            }

            $this->closeModal();
            $this->loadData();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function hapus($bidangId)
    {
        try {
            $bidang = ModelsBidang::find($bidangId);
            if ($bidang) {
                $bidang->delete();
                session()->flash('message', 'Bidang berhasil dihapus!');
                $this->loadData();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->namaBidang = '';
        $this->bidangId = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Bidang', 'pageTitle' => 'Bidang'])]
    public function render()
    {
        $dataBidang = ModelsBidang::query()
            ->where('nama_bidang', 'like', "%{$this->searchBidang}%")
            ->latest()
            ->paginate(4);

        return view('livewire.admin.bidang.bidang', [
            'dataBidang' => $dataBidang
        ]);
    }
}
