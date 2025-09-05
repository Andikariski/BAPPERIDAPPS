<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Livewire\Component;

class Edit extends Component
{
    public Post $post;
    public $tag_ids = [];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->tag_ids = $post->tags->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'post.title' => 'required|string|max:255',
            'post.slug' => 'required|string|max:255|unique:posts,slug,' . $this->post->id,
            'post.content' => 'required|string',
            'post.category_id' => 'required|exists:categories,id',
        ]);

        $this->post->save();
        $this->post->tags()->sync($this->tag_ids);

        session()->flash('message', 'Post updated successfully.');
        return redirect()->route('admin.posts.index');
    }

    public function render()
    {
        return view('livewire.admin.posts.edit', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }
}
