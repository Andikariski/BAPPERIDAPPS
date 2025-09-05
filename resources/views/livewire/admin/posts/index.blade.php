<div>
    @php
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('dashboard')],
            ['name' => 'Artikel', 'url' => route('admin.posts.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />

    <div class="mt-10">
        <div class="flex justify-between items-center mb-4">
            <input type="text" placeholder="Search..." wire:model.live="search" class="border rounded px-3 py-2">
            <a href="{{ route('admin.posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                + New Post
            </a>
        </div>

        <table class="w-full rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-300 text-left">
                    <th class="px-4 py-4">Judul Artikel</th>
                    <th class="px-4 py-4">Kategori</th>
                    <th class="px-4 py-4">Status Publikasi</th>
                    <th class="px-4 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr class="odd:bg-white even:bg-gray-100">
                        <td class="px-4 py-3">{{ $post->title }}</td>
                        <td class="px-4 py-3">{{ $post->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($post->status) }}</td>
                        <td class="px-4 py-3 flex gap-4">
                            <div class="bg-blue-500 text-white px-4 py-1 rounded-lg text-sm">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="">edit</a>
                            </div>
                            <button wire:click="delete({{ $post->id }})"
                                class="bg-red-500 text-white px-4 py-1 rounded-lg text-sm">hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
