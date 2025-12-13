<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('components.layouts.app');
    return redirect()->route('admin.dashboard');
    // Untuk redirect login
    // return redirect()->route('login');
});

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    // ->middleware('guest');

// Route::get('/tutor/view', \App\Livewire\Admin\Tutor\TutorView::class)->name('tutor.view');
// Route::get('/kelas/view', \App\Livewire\Admin\Kelas\KelasView::class)->name('kelas.view');
// Route::get('/role/view', \App\Livewire\Admin\User\RoleList::class)->name('role.view');
// Route::get('/user/view', \App\Livewire\Admin\User\UserList::class)->name('user.list');
// Route::get('/tutor/view', \App\Livewire\Admin\Tutor\TutorView::class)->name('admin.tutor.tutor-view'); 

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tutor/view', \App\Livewire\Admin\Tutor\TutorView::class)->name('tutor.view');
    Route::get('/kelas/view', \App\Livewire\Admin\Kelas\KelasView::class)->name('kelas.view');
    Route::get('/role/view', \App\Livewire\Admin\User\RoleList::class)->name('role.view');
    Route::get('/user/view', \App\Livewire\Admin\User\UserList::class)->name('user.list');
    Route::get('/penggajian/view', \App\Livewire\Admin\Penggajian\PenggajianList::class)->name('penggajian.list');
    Route::get('/pertemuan/view', \App\Livewire\Admin\Pertemuan\PertemuanList::class)->name('pertemuan.list');
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard\Dashboard::class)->name('dashboard');
    Route::get('/presensi/view', \App\Livewire\Admin\Presensi\PresensiView::class)->name('presensi.view');
    
});