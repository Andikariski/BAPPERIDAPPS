<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'name' => 'Tag 1', 'slug' => 'tag-1'],
            ['id' => 2, 'name' => 'Tag 2', 'slug' => 'tag-2'],
            ['id' => 3, 'name' => 'Tag 3', 'slug' => 'tag-3'],
            ['id' => 4, 'name' => 'Tag 4', 'slug' => 'tag-4'],
        ];

        Tag::insert($data);       //
    }
}
