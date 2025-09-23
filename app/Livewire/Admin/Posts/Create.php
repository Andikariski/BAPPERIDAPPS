<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $featuredImage;
    public $seoTitle;
    public $seoDescription;
    public $status = 'draft';
    public $publishedAt;
    public $categoryId;
    public $tagIds = [];

    // data gambar
    public $imageNames = [];

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'categoryId' => 'required|exists:categories,id',
        ]);

        $coverPhoto = null;
        if ($this->featuredImage) {
            $coverPhoto = uniqid() . '.' . $this->featuredImage->extension();
            $this->featuredImage->storeAs('blog_cover_photo', $coverPhoto, 'public');
        }

        if (!$this->excerpt) {
            $this->excerpt = $this->generateExcerpt($this->content);
        }
        if (!$this->seoTitle) {
            $this->seoTitle = $this->title;
        }
        if (!$this->seoDescription) {
            $this->seoDescription = Str::limit(strip_tags($this->excerpt), 160);
        }
        $this->publishedAt = $this->status === 'published' ? now() : null;

        $post = Post::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'featured_image' => $coverPhoto,
            'seo_title' => $this->seoTitle,
            'seo_description' => $this->seoDescription,
            'category_id' => $this->categoryId,
            'published_at' => $this->publishedAt,
            'author_id' => auth()->id(),
        ]);

        $post->tags()->sync($this->tagIds);

        session()->flash('message', 'Post created successfully.');
        return redirect()->route('admin.posts.index');
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
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
        $this->content .= '<img style="" src="' . $url . '"alt="uploades image">';
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

    private function generateExcerpt($content)
    {
        // Ambil paragraf pertama <p>...</p>
        preg_match('/<p>(.*?)<\/p>/i', $content, $matches);
        $paragraph = $matches[1] ?? strip_tags($content);
        // Potong 160 karakter untuk SEO
        return Str::limit(strip_tags($paragraph), 160);
    }

    #[Layout('components.layouts.admin', ['title' => 'Admin | Berita', 'pageTitle' => 'Berita'])]
    public function render()
    {
        return view('livewire.admin.posts.create', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }
}
