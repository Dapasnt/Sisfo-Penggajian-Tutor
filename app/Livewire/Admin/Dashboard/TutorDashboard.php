<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('GTC | Dashboard')]
class TutorDashboard extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Default values (agar tidak error jika user bukan tutor)
        $estimasiGaji = 0;
        $jumlahHadir = 0;
        $statusTerakhir = 'Belum ada data';
        $warnaStatus = 'secondary'; // Untuk warna badge (opsional)
        $tutorId = $user->tutor->id;

        // --- QUERY 1: Estimasi Gaji Bulan Ini ---
        $presensi = Pertemuan::with('tutor')
            ->where('id_tutor', $tutorId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // --- QUERY 2: Jumlah Pertemuan "Hadir" Bulan Ini ---
        $jumlahHadir = Pertemuan::query()
            ->where('id_tutor', $tutorId)
            ->where('status', 'Hadir') // Filter status spesifik
            ->whereMonth('tgl_pertemuan', now()->month)
            ->whereYear('tgl_pertemuan', now()->year)
            ->count();

        // --- QUERY 3: Status Terakhir Diupload ---
        // Menggunakan latest() untuk mengambil data berdasarkan created_at terakhir
        $lastMeeting = Pertemuan::query()
            ->where('id_tutor', $tutorId)
            ->latest() // Sama dengan orderBy('created_at', 'desc')
            ->first();

        if ($lastMeeting) {
            $statusTerakhir = $lastMeeting->status;

            // Bonus: Tentukan warna badge berdasarkan status
            $warnaStatus = match ($statusTerakhir) {
                'Hadir', 'Selesai', 'Terverifikasi' => 'success', // Hijau
                'Pending', 'Proses' => 'warning', // Kuning
                'Return', 'Batal' => 'danger',  // Merah
                default => 'primary' // Biru
            };
        }


        return view('livewire.admin.dashboard.tutor-dashboard', [
            'presensi'   => $presensi,
            'jumlah_hadir'    => $jumlahHadir,
            'status_terakhir' => $statusTerakhir,
            'warna_status'    => $warnaStatus
        ]);
    }
}
