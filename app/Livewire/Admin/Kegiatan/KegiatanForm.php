<?php

namespace App\Livewire\Admin\Kegiatan;

use App\Models\Bidang;
use App\Models\FotoKegiatan;
use App\Models\Kegiatan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class KegiatanForm extends Component
{

    use WithFileUploads;
    public ?Kegiatan $kegiatan = null;
    public bool $isEdit = false;

    #[Rule('required|exists:tbl_bidang,id')]
    public $fkid_bidang = '';

    #[Rule('required|string|max:255')]
    public $nama_kegiatan = '';

    #[Rule('required|string')]
    public $deskripsi_kegiatan = '';

    #[Rule('nullable|array')]
    public $photos = [];

    #[Rule('nullable|array')]
    public $captions = [];

    public $existingPhotos = [];
    public $photoToDelete = [];

    public function mount($kegiatan = null)
    {
        if ($kegiatan) {
            if ($kegiatan instanceof \App\Models\Kegiatan) {
                $kegiatanModel = $kegiatan->load('fotoKegiatan');
                $this->existingPhotos = $kegiatanModel['fotoKegiatan'];
            } else {
                $kegiatanModel = \App\Models\Kegiatan::findOrFail($kegiatan);
            }

            $this->isEdit = true;
            $this->kegiatan = $kegiatanModel;

            $this->fkid_bidang = $kegiatanModel->fkid_bidang;
            $this->nama_kegiatan = $kegiatanModel->nama_kegiatan;
            $this->deskripsi_kegiatan = $kegiatanModel->deskripsi_kegiatan;
        } else {
            $this->isEdit = false;
            $this->kegiatan = new \App\Models\Kegiatan();
        }
    }

    // manajemen foto
    public function loadExistingPhotos()
    {
        $this->existingPhotos = $this->kegiatan->fotoKegiatan->map(function ($foto) {
            return [
                'id' => $foto->id,
                'url' => $foto->url,
                'thumbnail_url' => $foto->thumbnail_url,
                'caption' => $foto->caption,
                'nama_file' => $foto->nama_file,
                'urutan' => $foto->urutan,
                'is_main' => $foto->is_main,
            ];
        })->toArray();
    }
    public function updatedPhotos()
    {
        $this->validate([
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);
    }

    public function removePhoto($index)
    {
        if (isset($this->photos[$index])) {
            // unset dulu agar tidak error index
            unset($this->photos[$index]);

            // reset array key supaya indeks rapi lagi
            $this->photos = array_values($this->photos);

            // kalau ada captions ikut dihapus
            if (isset($this->captions[$index])) {
                unset($this->captions[$index]);
                $this->captions = array_values($this->captions);
            }
        }
    }
    public function removeExistingPhoto($photoId)
    {
        $this->photoToDelete[] = $photoId;

        $this->existingPhotos = $this->existingPhotos->reject(function ($photo) use ($photoId) {
            return $photo->id == $photoId;
        });
    }

    public function setMainPhoto($photoId)
    {
        foreach ($this->existingPhotos as $photo) {
            $photo['is_main'] = $photo['id'] == $photoId;
        }
        FotoKegiatan::where('fkid_kegiatan', $this->kegiatan->id)->update(['is_main' => false]);
        FotoKegiatan::where('id', $photoId)->update(['is_main' => true]);

        $this->dispatch('photo-updated', 'Foto utama berhasil diubah');
    }
    public function updatePhotoOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            FotoKegiatan::where('id', $id)->update(['urutan' => $index + 1]);
        }
        $this->loadExistingPhotos();
        $this->dispatch('photo-updated', 'Urutan foto berhasil diubah');
    }
    public function uploadPhotos()
    {
        $urutanTerakhir = $this->kegiatan->fotoKegiatan()->max('urutan') ?? 0;
        $isFirstPhoto = $this->kegiatan->fotoKegiatan()->count() === 0;

        foreach ($this->photos as $index => $photo) {
            $namaFileAsli = $photo->getClientOriginalName();
            $ekstensi = $photo->getClientOriginalExtension();
            $namaFileBaru = Str::slug(pathinfo($namaFileAsli, PATHINFO_FILENAME))
                . '_' . time() . '_' . uniqid() . '.' . $ekstensi;

            // simpan file ke disk public
            $direktori = "kegiatan/{$this->kegiatan->id}";
            $pathFile = $photo->storeAs($direktori, $namaFileBaru, 'public');

            // absolute path untuk manipulasi gambar
            $absolutePath = storage_path('app/public/' . $pathFile);

            // resize & watermark
            $this->resizeImage($pathFile);
            FotoKegiatan::addWatermark($pathFile, config('app.name'));

            // generate thumbnail
            $thumbnailPath = FotoKegiatan::generatethumbnail($pathFile);

            // dimensi
            $imageInfo = getimagesize($absolutePath);

            // simpan database
            FotoKegiatan::create([
                'fkid_kegiatan' => $this->kegiatan->id,
                'nama_file'     => $namaFileAsli,
                'path_file'     => $pathFile,              // relatif ke disk public
                'path_thumbnail' => $thumbnailPath,
                'mime_type'     => $photo->getMimeType(),
                'ukuran_file'   => $photo->getSize(),
                'urutan'        => ++$urutanTerakhir,
                'caption'       => $this->captions[$index] ?? null,
                'width'         => $imageInfo[0] ?? null,
                'height'        => $imageInfo[1] ?? null,
                'is_main'       => $isFirstPhoto && $urutanTerakhir === 1,
            ]);
        }
    }

    private static function getImageManager()
    {
        $driver = config('image.driver', 'gd');
        return $driver === 'imagick'
            ? ImageManager::imagick()
            : ImageManager::gd();
    }

    private function resizeImage(string $path, ?int $maxWidth = null, ?int $maxHeight = null)
    {
        $maxWidth = $maxWidth ?: config('image.resize.max_width', 1920);
        $maxHeight = $maxHeight ?: config('image.resize.max_height', 1080);
        $quality = config('image.quality.resize', 85);

        $fullPath = storage_path(config('image.paths.storage', 'app/public') . '/' . $path);

        $image = self::getImageManager()->read($fullPath);

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);

            $encoded = $image->toJpeg(quality: $quality);
            file_put_contents($fullPath, $encoded);
        }
    }

    // save data
    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // simpan atau update kegiatan
            if ($this->isEdit) {
                $this->kegiatan->update([
                    'fkid_bidang' => $this->fkid_bidang,
                    'nama_kegiatan' => $this->nama_kegiatan,
                    'deskripsi_kegiatan' => $this->deskripsi_kegiatan
                ]);
            } else {
                $this->kegiatan = Kegiatan::create([
                    'fkid_bidang' => $this->fkid_bidang,
                    'nama_kegiatan' => $this->nama_kegiatan,
                    'deskripsi_kegiatan' => $this->deskripsi_kegiatan
                ]);
            }

            // hapus foto yang di tandai untuk di hapus
            if (!empty($this->photoToDelete)) {
                $photosToDelete = FotoKegiatan::whereIn('id', $this->photoToDelete)->get();
                foreach ($photosToDelete as $photo) {
                    $photo->hapusFile();
                    $photo->delete();
                }
            }

            //upload foto baru
            if (!empty($this->photos)) {
                $this->uploadPhotos();
            }

            DB::commit();

            $this->dispatch($this->isEdit ? 'success-edit-data' : 'success-add-data');

            return redirect()->route('admin.kegiatan.index');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan kegiatan: ' . $e->getMessage());
        }
    }
    #[Layout('components.layouts.admin', ['title' => 'Admin | Kegiatan', 'pageTitle' => 'Kegiatan'])]
    public function render()
    {
        $dataBidang = Bidang::orderBy('nama_bidang')->get();
        return view('livewire.admin.kegiatan.kegiatan-form', ['dataBidang' => $dataBidang]);
    }
}
