<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dummy Users
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password'   => bcrypt('12345678'),
        ]);

        // Categories
        $categories = collect(['Technology', 'Business', 'Lifestyle'])->map(function ($name) {
            return Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "All about $name",
            ]);
        });

        // Tags
        $tags = collect(['Laravel', 'PHP', 'Livewire', 'SEO', 'Tips'])->map(function ($name) {
            return Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        });

        // Posts
        foreach (range(1, 100) as $i) {
            $title = "Sample Blog Post $i";
            $post = Post::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => "This is an excerpt for blog post $i.",
                'content' => "<p>This is the content for blog post $i. Lorem ipsum dolor sit amet.</p>",
                'status' => 'published',
                'author_id' => $user->id,
                'category_id' => $categories->random()->id,
                'published_at' => now(),
            ]);

            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));
        }
    }
}
