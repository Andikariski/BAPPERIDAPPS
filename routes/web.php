<?php

use App\Livewire\Admin\Berita\Berita;
use App\Livewire\Admin\Berita\BeritaDetail;
use App\Livewire\Admin\Berita\BeritaForm;
use App\Livewire\Admin\Bidang\Bidang;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Jabatan\Jabatan;
use App\Livewire\Admin\Kegiatan\Kegiatan;
use App\Livewire\Admin\Kegiatan\KegiatanDetail;
use App\Livewire\Admin\Kegiatan\KegiatanForm;
use App\Livewire\Admin\Pegawai\Index;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Admin\Posts\Index as PostIndex;
use App\Livewire\Admin\Posts\Create as PostCreate;
use App\Livewire\Admin\Posts\Edit as PostEdit;
use App\Livewire\Admin\RAP\RAP;

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

Route::get('/pegawai',\App\Livewire\Pegawai\Index::class)->name('pegawai');

Route::get('/dokumenPublik',\App\Livewire\Dokumen\Index::class)->name('dokumenpublik');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // route kegiatan
    Route::get('/kegiatan', Kegiatan::class)->name('admin.kegiatan.index');
    Route::get('/kegiatan/create', KegiatanForm::class)->name('admin.kegiatan.create');
    Route::get('/kegiatan/{kegiatan}/edit', KegiatanForm::class)->name('admin.kegiatan.edit');
    Route::get('/kegiatan/{kegiatan}', KegiatanDetail::class)->name('admin.kegiatan.detail');
    // route bidang
    Route::get('/bidang', Bidang::class)->name('admin.bidang.index');
    // route Jabatan
    Route::get('/jabatan', Jabatan::class)->name('admin.jabatan.index');
    // route RAP
    Route::get('/rap', RAP::class)->name('admin.rap.index');
    // route berita
    Route::get('/berita', Berita::class)->name('admin.berita.index');
    Route::get('/berita/create', BeritaForm::class)->name('admin.berita.create');
    Route::get('/berita/{berita}/edit', BeritaForm::class)->name('admin.berita.edit');
    Route::get('/berita/{berita}', BeritaDetail::class)->name('admin.berita.detail');

    //Route Pegawai
    Route::get('/pegawai-admin', Index::class)->name('pegawai-admin');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
