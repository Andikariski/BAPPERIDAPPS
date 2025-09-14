<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Kegiatan\Kegiatan;
use App\Livewire\Admin\Kegiatan\KegiatanDetail;
use App\Livewire\Admin\Kegiatan\KegiatanForm;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Admin\Posts\Index as PostIndex;
use App\Livewire\Admin\Posts\Create as PostCreate;
use App\Livewire\Admin\Posts\Edit as PostEdit;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
Route::get('/', function () {
    return view('homepage');
})->name('home');
Route::get('/strukturOrganisasi', function () {
    return view('strukturOrganisasi');
})->name('strukrOrganisasi');
Route::get('/tupoksi', function () {
    return view('tupoksi');
})->name('tupoksi');
Route::get('/blog', \App\Livewire\Blog\Index::class)->name('blog.index');
Route::get('/blog/{slug}', \App\Livewire\Blog\Show::class)->name('blog.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/posts', PostIndex::class)->name('admin.posts.index');
    Route::get('/posts/create', PostCreate::class)->name('admin.posts.create');
    Route::get('/posts/{post}/edit', PostEdit::class)->name('admin.posts.edit');
    // });
    Route::get('/kegiatan', Kegiatan::class)->name('admin.kegiatan.index');
    Route::get('/kegiatan/create', KegiatanForm::class)->name('admin.kegiatan.create');
    Route::get('/kegiatan/{kegiatan}/edit', KegiatanForm::class)->name('admin.kegiatan.edit');
    Route::get('/kegiatan/{kegiatan}', KegiatanDetail::class)->name('admin.kegiatan.detail');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
