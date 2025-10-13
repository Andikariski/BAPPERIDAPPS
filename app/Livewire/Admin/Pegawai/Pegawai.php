<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Bidang;
use App\Models\Jabatan;
use App\Models\Pegawai as ModelsPegawai;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Pegawai extends Component
{
    use WithPagination;
    use WithFileUploads;

    //filtering
    public $searchPegawai = '';
    public $bidangFilter = '';
    public $jabatanFilter = '';

    // property data
    public $fkidBidang;
    public $fkidJabatan;
    public $nip;
    public $namaPegawai;
    public $fotoProfile;

    public $editingId = null;
    public $pegawaiId = null;
    public $isEdit = false;
    public $existingFotoProfile = null;

    // Modal state
    public $showModal = false;
    public $showDetailModal = false;
    public $modalTitle = '';


    public function updatedSearchPegawai()
    {
        $this->resetPage();
    }
    public function updatedBidangFilter()
    {
        $this->resetPage();
    }
    public function updatedJabatanFilter()
    {
        $this->resetPage();
    }

    protected function rules()
    {
        $rules = [
            'namaPegawai' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'fkidBidang' => 'required|exists:tbl_bidang,id',
            'fkidJabatan' => 'required|exists:tbl_jabatan,id',
        ];

        // Validasi thumbnail
        if ($this->fotoProfile) {
            $rules['fotoProfile'] = 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:5048';
        }

        return $rules;
    }

    public function openTambahModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->modalTitle = 'Tambah Pegawai Baru';
        $this->showModal = true;
    }

    public function openDetailModal($pegawaiId)
    {
        $pegawai = ModelsPegawai::find($pegawaiId);

        if ($pegawai) {
            $this->pegawaiId = $pegawai->id;
            $this->namaPegawai = $pegawai->nama_pegawai;
            $this->nip = $pegawai->nip;
            $this->fkidBidang = $pegawai->bidang->nama_bidang ?? '-belum ditentukan-';
            $this->fkidJabatan = $pegawai->jabatan->nama_jabatan ?? '-belum ditentukan-';
            $this->fotoProfile = $pegawai->foto_profile;
            $this->isEdit = true;
            $this->modalTitle = 'Detail Data Pegawai';
            $this->showDetailModal = true;
        }
    }

    public function openEditModal($pegawaiId)
    {
        $pegawai = ModelsPegawai::find($pegawaiId);

        if ($pegawai) {
            $this->pegawaiId = $pegawai->id;
            $this->namaPegawai = $pegawai->nama_pegawai;
            $this->nip = $pegawai->nip;
            $this->fkidBidang = $pegawai->bidang->id ?? null;
            $this->fkidJabatan = $pegawai->jabatan->id ?? null;
            $this->existingFotoProfile = $pegawai->foto_profile;
            $this->isEdit = true;
            $this->modalTitle = 'Edit Data Pegawai';
            $this->showModal = true;
        }
    }

    public function simpan()
    {
        $this->validate();

        try {

            $coverPhoto = $this->existingFotoProfile;

            // Handle thumbnail upload with better validation
            if ($this->fotoProfile) {
                // Validate file type and size
                $this->validate([
                    'fotoProfile' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5048'
                ]);

                // Delete old thumbnail if editing
                if ($this->isEdit && $this->existingFotoProfile) {
                    if (Storage::disk('public')->exists('foto_profil_pegawai/' . $this->existingFotoProfile)) {
                        Storage::disk('public')->delete('foto_profil_pegawai/' . $this->existingFotoProfile);
                    }
                }

                // Generate unique filename with original extension
                $extension = $this->fotoProfile->getClientOriginalExtension();
                $coverPhoto = time() . '_' . uniqid() . '.' . $extension;

                // Store the file
                $path = $this->fotoProfile->storeAs('foto_profil_pegawai', $coverPhoto, 'public');

                // Verify file was stored successfully
                if (!$path) {
                    throw new Exception('Gagal menyimpan file thumbnail');
                }
            }

            if ($this->isEdit) {
                // Update data
                $pegawai = ModelsPegawai::find($this->pegawaiId);
                $pegawai->update([
                    'nama_pegawai' => $this->namaPegawai,
                    'fkid_jabatan' => $this->fkidJabatan,
                    'fkid_bidang' => $this->fkidBidang,
                    'nip' => $this->nip,
                    'foto_profile' => $coverPhoto,
                ]);

                $this->dispatch('success-edit-data');
            } else {
                // Tambah data baru
                ModelsPegawai::create([
                    'nama_pegawai' => $this->namaPegawai,
                    'fkid_jabatan' => $this->fkidJabatan,
                    'fkid_bidang' => $this->fkidBidang,
                    'nip' => $this->nip,
                    'foto_profile' => $coverPhoto,
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

    #[On('delete-data-pegawai')]
    public function hapus($id)
    {
        try {
            $pegawai = ModelsPegawai::find($id);
            if ($pegawai) {
                // Delete thumbnail image
                if ($pegawai->foto_profile) {
                    $filePath = 'foto_profil_pegawai/' . $pegawai->foto_profile;
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
                $pegawai->delete();

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
        $this->showDetailModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->namaPegawai = '';
        $this->nip = '';
        $this->fkidBidang = '';
        $this->fkidJabatan = '';
        $this->fotoProfile = '';
        $this->pegawaiId = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }


    #[Layout('components.layouts.admin', ['title' => 'Admin | Pegawai', 'pageTitle' => 'Pegawai'])]
    public function render()
    {
        $dataPegawai = ModelsPegawai::query()
            ->with(['bidang', 'jabatan'])
            ->when($this->searchPegawai, function ($query) {
                $query->where('nama_pegawai', 'like', '%' . $this->searchPegawai . '%');
            })
            ->when($this->bidangFilter, function ($query) {
                $query->where('fkid_bidang', $this->bidangFilter);
            })
            ->when($this->jabatanFilter, function ($query) {
                $query->where('fkid_jabatan', $this->jabatanFilter);
            })
            ->paginate(10);
        $dataBidang = Bidang::all();
        $dataJabatan = Jabatan::all();

        return view('livewire.admin.pegawai.pegawai', [
            'dataPegawai' => $dataPegawai,
            'dataJabatan' => $dataJabatan,
            'dataBidang' => $dataBidang,
        ]);
    }
}
