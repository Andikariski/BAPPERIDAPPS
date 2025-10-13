<div>
    @php
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Data RAP', 'url' => route('admin.rap.index')],
        ];
    @endphp
    <x-breadcrumb :items="$breadcrumbs" />
    <div class="my-4">
        <p>hello rap page</p>
    </div>
</div>
