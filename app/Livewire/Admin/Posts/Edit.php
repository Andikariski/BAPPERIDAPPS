<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $post = [];
    public $tagIds = [];
    public $featuredImage;
    public $contentPost;

    public function mount(Post $post)
    {
        $this->post = $post->toArray();
        $this->contentPost = $this->post['content'];
        $this->tagIds = $post->tags->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->dispatch('populate-quill', contentPost: $this->contentPost);
    }

    public function update()
    {
        $validated = $this->validate([
            'post.title' => 'required|string|max:255',
            'post.slug' => 'required|string|max:255|unique:posts,slug,' . $this->post['id'],
            'post.content' => 'required|string',
            'post.category_id' => 'required|exists:categories,id',
            'post.status' => 'required|in:draft,published',
        ]);

        $model = Post::findOrFail($this->post['id']);
        if ($this->featuredImage) {
            // Hapus file lama jika ada
            if (!empty($model->featured_image) && Storage::disk('public')->exists('blog_cover_photo/' . $model->featured_image)) {
                Storage::disk('public')->delete('blog_cover_photo/' . $model->featured_image);
            }

            // Simpan file baru
            $coverPhoto = uniqid() . '.' . $this->featuredImage->extension();
            $this->featuredImage->storeAs('blog_cover_photo', $coverPhoto, 'public');

            // Update field featured_image
            $validated['post']['featured_image'] = $coverPhoto;
        }

        $model->update($validated['post']);
        $model->tags()->sync($this->tagIds);

        session()->flash('message', 'Post updated successfully.');
        return redirect()->route('admin.posts.index');
    }

    public function uploadImage($image)
    {
        // inisiasi imageManager dengan driver GD atau Imagick
        $manager = new ImageManager(new Driver());
        //  ambil data base64 setelah "data:image/png;base64,"
        $imageData = substr($image, strpos($image, ',') + 1);
        $imageData = base64_decode($imageData);
        // nama file unik (20 char terakhir + .png)
        $length = strlen($imageData);
        $lastChars = substr(md5($imageData), -20); // lebih aman pakai md5
        $filename = $lastChars . '.png';
        // baca gambar
        $img = $manager->read($imageData);
        // resize tinggi ke 400px dan jaga aspect ratio
        $img = $img->scale(height: 400);
        // simpan ke storage
        Storage::disk('public')->put('blog_photos/' . $filename, (string) $img->encode());
        // URL gambar
        $url = Storage::url('blog_photos/' . $filename);
        // tambahkan tag <img> ke konten quill js
        $this->contentPost .= '<img style="" src="' . $url . '"alt="uploades image">';
        // dispatch event ke livewire
        return $this->dispatch('blog-image-uploaded', $url);
    }

    public function deleteImage($imageUrl)
    {
        // Ambil nama file dari URL
        $filename = basename(parse_url($imageUrl, PHP_URL_PATH));
        // Tentukan path relatif di storage
        $path = 'blog_photos/' . $filename;
        // delete gambar
        Storage::disk('public')->delete($path);
    }
    #[Layout('components.layouts.admin', ['title' => 'Admin | Berita', 'pageTitle' => 'Berita'])]
    public function render()
    {
        return view('livewire.admin.posts.edit', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }
}
