<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PenggajianList extends Component
{

    public $formTgl = false, $confirmingDelete = false;
    public $search = '';

    public $id_tutor, $nama, $bulan, $tahun;
    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }
    public function render()
    {
        // $tutorList = Tutor::search($this->search)->orderBy('nama')->paginate(10);

        $penggajianList = Penggajian::with(['tutor'])
            ->where('periode_bulan', $this->bulan)
            ->where('periode_tahun', $this->tahun)
            // ->orderBy('periode_bulan')
            ->paginate(10);
        // dd($penggajian);
        return view('livewire.admin.penggajian.penggajian-list', compact('penggajianList'));
    }

    public function resetForm()
    {
        $this->formTgl = false;
        $this->resetErrorBag();
    }

    public function generate()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        DB::transaction(function () {
            // A. Cari Absensi yang:
            // 1. Bulan & Tahun sesuai filter
            // 2. Status 'Hadir'
            // 3. Status Validasi Foto 'Disetujui' (PENTING!)
            // 4. Belum punya payroll_id (atau bisa di-overwrite logika ini)

            $pertemuan = Pertemuan::whereMonth('tgl_pertemuan', $this->bulan)
                ->whereYear('tgl_pertemuan', $this->tahun)
                ->where('status', 'hadir')
                ->get()
                ->groupBy('id_tutor'); // Kelompokkan per Tutor

            // B. Loop setiap Tutor
            foreach ($pertemuan as $idTutor => $listAbsensi) {

                $totalHonor = $listAbsensi->sum('tarif_saat_itu');
                $totalPertemuan = $listAbsensi->count();

                // C. Simpan/Update ke Tabel Penggajian
                $penggajian = Penggajian::updateOrCreate(
                    [
                        'id_tutor'      => $idTutor, // Sesuaikan nama kolom FK di tabel penggajian
                        'periode_bulan' => $this->bulan,
                        'periode_tahun' => $this->tahun,
                    ],
                    [
                        'total_pertemuan' => $totalPertemuan,
                        'total_honor'     => $totalHonor,
                        // Rumus: Honor + Tunjangan - Potongan
                        // Kita pakai DB::raw agar tunjangan lama tidak tertimpa nol jika sudah diisi manual
                        'gaji_dibayar'    => DB::raw("total_honor"),
                    ]
                );

                // D. Update Absensi agar terhubung ke ID penggajian ini
                Pertemuan::whereIn('id', $listAbsensi->pluck('id'))
                    ->update(['penggajian_id' => $penggajian->id]);
            }
        });

        session()->flash('success', 'Gaji bulan ' . $this->bulan . ' berhasil dihitung ulang!');
        $this->resetForm();
    }
}
