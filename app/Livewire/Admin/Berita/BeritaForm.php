<?php

namespace App\Livewire\Admin\Berita;

use App\Models\Berita;
use App\Models\Bidang;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BeritaForm extends Component
{
    use WithFileUploads;
    public ?Berita $berita = null;

    public $judulBerita;
    public $kontenBerita;
    public $fotoThumbnail;
    public $tagIds = [];
    public $statusPublikasi = 'draft';
    public $bidangPelaksanaId;
    public $slug;

    // Properties untuk edit mode
    public $isEdit = false;
    public $beritaId = null;
    public $existingThumbnail = null;
    public $imageNames = [];

    public function mount($berita = null)
    {
        if ($berita) {
            if ($berita instanceof \App\Models\Berita) {
                $beritaModel = $berita->load('tags');
                $this->existingThumbnail = $beritaModel['foto_thumbnail'];
            } else {
                $beritaModel = \App\Models\Berita::findOrFail($berita);
            }
            $this->isEdit = true;
            $this->berita = $beritaModel;
            $this->beritaId = $beritaModel->id;
            $this->judulBerita = $beritaModel->judul_berita;
            $this->slug = $beritaModel->slug;
            $this->kontenBerita = $beritaModel->konten_berita;
            $this->bidangPelaksanaId = $beritaModel->fkid_bidang;
            $this->kontenBerita = $beritaModel->konten_berita;
            $this->tagIds = $berita->tags->pluck('id')->toArray();
            $this->statusPublikasi = $berita->status_publikasi;
            $this->dispatch('populate-quill', contentPost: $this->kontenBerita);
        } else {
            $this->isEdit = false;
            $this->berita = new \App\Models\Berita();
        }
    }

    protected function rules()
    {
        $rules = [
            'judulBerita' => 'required|string|max:255',
            'kontenBerita' => 'required|string',
            'bidangPelaksanaId' => 'required|exists:tbl_bidang,id',
            'statusPublikasi' => 'required|in:draft,published',
            'tagIds' => 'nullable',
            'tagIds.*' => 'exists:tbl_tag,id',
        ];

        // Validasi slug dengan pengecualian untuk edit mode
        if ($this->isEdit && $this->berita) {
            $rules['slug'] = 'required|string|max:255|unique:tbl_berita,slug,' . $this->berita->id;
        } else {
            $rules['slug'] = 'required|string|max:255|unique:tbl_berita,slug';
        }

        // Validasi thumbnail
        if ($this->fotoThumbnail) {
            $rules['fotoThumbnail'] = 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        try {
            $coverPhoto = $this->existingThumbnail; // Keep existing thumbnail for edit

            // Handle thumbnail upload with better validation
            if ($this->fotoThumbnail) {
                // Validate file type and size
                $this->validate([
                    'fotoThumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
                ]);

                // Delete old thumbnail if editing
                if ($this->isEdit && $this->existingThumbnail) {
                    if (Storage::disk('public')->exists('foto_thumbnail_berita/' . $this->existingThumbnail)) {
                        Storage::disk('public')->delete('foto_thumbnail_berita/' . $this->existingThumbnail);
                    }
                }

                // Generate unique filename with original extension
                $extension = $this->fotoThumbnail->getClientOriginalExtension();
                $coverPhoto = time() . '_' . uniqid() . '.' . $extension;

                // Store the file
                $path = $this->fotoThumbnail->storeAs('foto_thumbnail_berita', $coverPhoto, 'public');

                // Verify file was stored successfully
                if (!$path) {
                    throw new Exception('Gagal menyimpan file thumbnail');
                }
            }

            $data = [
                'judul_berita' => $this->judulBerita,
                'konten_berita' => $this->kontenBerita,
                'slug' => $this->slug,
                'foto_thumbnail' => $coverPhoto,
                'fkid_bidang' => $this->bidangPelaksanaId,
                'status_publikasi' => $this->statusPublikasi,
            ];

            if ($this->isEdit) {
                // Update existing berita
                $berita = Berita::findOrFail($this->beritaId);
                $berita->update($data);
                $message = 'Berita berhasil diupdate!';
            } else {
                // Create new berita
                $data['author_id'] = auth()->id();
                $berita = Berita::create($data);
                $message = 'Berita berhasil ditambahkan!';
            }

            // Sync tags
            $berita->tags()->sync($this->tagIds);

            $this->dispatch('success-add-data');
            return redirect()->route('admin.berita.index');
        } catch (Exception $e) {
            dump($e->getMessage());
            $this->dispatch('failed-add-data', ['message' => $e->getMessage()]);
        }
    }

    public function updatedJudulBerita($value)
    {
        $this->slug = Str::slug($value);
    }
    public function uploadImage($image)
    {
        try {
            // Initialize ImageManager with GD driver
            $manager = new ImageManager(new Driver());

            // Get base64 data after "data:image/png;base64,"
            $imageData = substr($image, strpos($image, ',') + 1);
            $imageData = base64_decode($imageData);

            // Generate unique filename
            $filename = uniqid() . '.png';

            // Read and process image
            $img = $manager->read($imageData);
            $img = $img->scale(height: 400);

            // Save to storage
            Storage::disk('public')->put('foto-foto-berita/' . $filename, (string) $img->encode());

            // Get URL
            $url = Storage::url('foto-foto-berita/' . $filename);

            // Add to content
            $this->kontenBerita .= '<img src="' . $url . '" alt="uploaded image" style="max-width: 100%; height: auto;">';

            // Dispatch event
            return $this->dispatch('blog-image-uploaded', $url);
        } catch (Exception $e) {
            $this->dispatch('image-upload-error', ['message' => 'Gagal upload gambar: ' . $e->getMessage()]);
        }
    }

    public function deleteImage($imageUrl)
    {
        try {
            // Get filename from URL
            $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
            $path = 'foto-foto-berita/' . $filename;

            // Delete image
            Storage::disk('public')->delete($path);

            // Remove from content
            $this->kontenBerita = str_replace(
                '<img src="' . $imageUrl . '" alt="uploaded image" style="max-width: 100%; height: auto;">',
                '',
                $this->kontenBerita
            );
        } catch (Exception $e) {
            $this->dispatch('image-delete-error', ['message' => 'Gagal menghapus gambar: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.berita.berita-form', [
            'tags' => Tag::all(),
            'dataBidang' => Bidang::all()
        ])
            ->layout('components.layouts.admin', [
                'title' => $this->isEdit ? 'Admin | Edit Berita' : 'Admin | Tambah Berita',
                'pageTitle' => $this->isEdit ? 'Edit Berita' : 'Tambah Berita',
            ]);
    }
}
