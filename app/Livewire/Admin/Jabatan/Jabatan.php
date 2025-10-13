<?php

namespace App\Livewire\Admin\Jabatan;

use App\Models\Jabatan as ModelsJabatan;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Jabatan extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255')]
    public $namaJabatan = '';
    #[Rule('required|integer')]
    public $kelasJabatan = 5;
    public $searchJabatan = '';

    public $editingId = null;
    public $jabatanId = null;
    public $isEdit = false;

    // Modal state
    public $showModal = false;
    public $modalTitle = '';

    protected $messages = [
        'namaJabatan.required' => 'Nama Jabatan harus diisi.',
        'namaJabatan.max' => 'Nama Jabatan maksimal 255 karakter.'
    ];

    public function openTambahModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->modalTitle = 'Tambah Bidang Baru';
        $this->showModal = true;
    }

    public function openEditModal($jabatanId)
    {
        $bidang = ModelsJabatan::find($jabatanId);

        if ($bidang) {
            $this->jabatanId = $bidang->id;
            $this->namaJabatan = $bidang->nama_jabatan;
            $this->kelasJabatan = $bidang->kelas_jabatan;
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
                $bidang = ModelsJabatan::find($this->jabatanId);
                $bidang->update([
                    'nama_jabatan' => $this->namaJabatan,
                    'kelas_jabatan' => $this->kelasJabatan
                ]);

                $this->dispatch('success-edit-data');
            } else {
                // Tambah data baru
                ModelsJabatan::create([
                    'nama_jabatan' => $this->namaJabatan,
                    'kelas_jabatan' => $this->kelasJabatan,
                ]);

                $this->dispatch('success-add-data');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            if ($this->isEdit) {
                $this->dispatch('failed-edit-data');
            } else {
                $this->dispatch('failed-add-data');
            }
        }
    }

    #[On('delete-data-bidang')]
    public function hapus($id)
    {
        try {
            $bidang = ModelsJabatan::find($id);
            if ($bidang) {
                $bidang->delete();

                $this->dispatch('success-delete-data');
                $this->closeModal();
            }
        } catch (\Exception $e) {
            $this->dispatch('failed-delete-data');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->namaJabatan = '';
        $this->jabatanId = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Jabatan', 'pageTitle' => 'Jabatan'])]
    public function render()
    {
        $dataJabatan = ModelsJabatan::query()
            ->where('nama_jabatan', 'like', "%{$this->searchJabatan}%")
            ->latest()
            ->paginate(10);

        return view('livewire.admin.jabatan.jabatan', [
            'dataJabatan' => $dataJabatan
        ]);
    }
}
