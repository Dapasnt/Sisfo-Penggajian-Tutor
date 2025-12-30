<?php

use App\Http\Controllers\CetakSlip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('components.layouts.app');
    // return redirect()->route('admin.dashboard');
    // Untuk redirect login
    // return redirect()->route('login');
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    // ->middleware('guest');

Route::get('/logout', function () {
    Auth::logout();
    return redirect(route('login'));
})->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/tutor/list', \App\Livewire\Admin\Tutor\TutorList::class)->name('tutor.list');

    Route::get('/kelas/list', \App\Livewire\Admin\Program\KelasList::class)->name('kelas.list');
    Route::get('/jenjang/list', \App\Livewire\Admin\Program\JenjangList::class)->name('jenjang.list');
    Route::get('/durasi/list', \App\Livewire\Admin\Program\DurasiList::class)->name('durasi.list');

    Route::get('/role/list', \App\Livewire\Admin\User\RoleList::class)->name('role.list');
    Route::get('/user/list', \App\Livewire\Admin\User\UserList::class)->name('user.list');

    Route::get('/penggajian/cetak-slip/{id}', [CetakSlip::class, 'cetakSlip'])->name('penggajian.cetak');
    Route::get('/penggajian/list', \App\Livewire\Admin\Penggajian\PenggajianList::class)->name('penggajian.list');
    Route::get('/pertemuan/list', \App\Livewire\Admin\Pertemuan\PertemuanList::class)->name('pertemuan.list');

    Route::get('/dashboard', \App\Livewire\Admin\Dashboard\Dashboard::class)->name('dashboard');
    Route::get('/tutor/dashboard', \App\Livewire\Admin\Dashboard\TutorDashboard::class)->name('tutor.dashboard');

    Route::get('/presensi/list', \App\Livewire\Admin\Presensi\Presensilist::class)->name('presensi.list');
    Route::get('/laporan/laporan-gaji', \App\Livewire\Admin\Laporan\LaporanGaji::class)->name('laporan.laporan-gaji');
    
});