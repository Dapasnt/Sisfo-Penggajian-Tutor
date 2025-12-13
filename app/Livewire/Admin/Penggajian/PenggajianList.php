<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PenggajianList extends Component
{

    use WithPagination;
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
            $listPertemuan = Pertemuan::whereMonth('created_at', $this->bulan)
                ->whereYear('created_at', $this->tahun)
                ->where('status', 'Hadir')
                ->get();

            if ($listPertemuan->isEmpty()) {
                return "Tidak ada data pertemuan untuk periode ini.";
            }

            $grouped = $listPertemuan->groupBy('id_tutor');

            foreach ($grouped as $idTutor => $absenTutor) {
                // 1. Hitung Total Honor dan Jumlah Pertemuan
                $totalHonor = $absenTutor->sum('tarif_saat_itu');
                $jumlahPertemuan = $absenTutor->count();

                // 2. Simpan atau Update ke Tabel Penggajian
                $gaji = Penggajian::updateOrCreate(
                    [
                        // Kunci Pencarian (Where)
                        'id_tutor'      => $idTutor,
                        'periode_bulan' => $this->bulan,
                        'periode_tahun' => $this->tahun,
                    ],
                    [
                        'total_pertemuan' => $jumlahPertemuan,
                        'total_honor'     => $totalHonor,
                        'gaji_dibayar'    => DB::raw('total_honor'),

                        // Status pembayaran jangan diubah kalau sudah lunas
                        // 'status' => 'Pending' (Default dari database)
                    ]
                );

                // 3. KUNCI DATA PERTEMUAN (Linking)
                // Update kolom 'id_penggajian' di tabel pertemuan agar terhubung ke slip gaji ini
                // Ambil semua ID pertemuan milik tutor ini di bulan ini
                $idPertemuanTutor = $absenTutor->pluck('id');

                Pertemuan::whereIn('id', $idPertemuanTutor)
                    ->update(['id_penggajian' => $gaji->id]);
            }
        });

        session()->flash('success', 'Perhitungan gaji periode ' . $this->bulan . '-' . $this->tahun . ' selesai!');

        // Reset tampilan ke mode list
        $this->formTgl = false;
        $this->resetPage();
    }
}
