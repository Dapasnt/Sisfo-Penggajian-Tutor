<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | Penggajian')]
class PenggajianList extends Component
{

    use WithPagination;
    public $formTgl = false, $confirmingDelete = false, $showPreviewModal = false;
    public $search = '';

    public $id_tutor, $nama, $bulan, $tahun, $jmlKelas, $selectedGaji;

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function mount()
    {
        $this->bulan = date('m');
        $this->tahun = date('Y');
    }
    public function render()
    {
        $penggajianList = Penggajian::with(['tutor'])
            ->where('periode_bulan', $this->bulan)
            ->where('periode_tahun', $this->tahun)
            ->search($this->search)
            ->paginate(10);
        return view('livewire.admin.penggajian.penggajian-list', compact('penggajianList'));
    }

    public function generate()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        try {
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
                    $totalDurasi = $absenTutor->sum(function ($item) {
                        return $item->durasi->durasi ?? 0;
                    });

                    $gaji = Penggajian::updateOrCreate(
                        [
                            'id_tutor'      => $idTutor,
                            'periode_bulan' => $this->bulan,
                            'periode_tahun' => $this->tahun,
                        ],
                        [
                            'total_pertemuan' => $jumlahPertemuan,
                            'total_honor'     => $totalHonor,
                            'gaji_dibayar'    => $totalHonor,
                            'total_durasi'    => $totalDurasi,
                        ]
                    );
                    $idPertemuanTutor = $absenTutor->pluck('id');
                    Pertemuan::whereIn('id', $idPertemuanTutor)
                        ->update(['id_penggajian' => $gaji->id_penggajian]);
                }
            });
            $this->formTgl = false;
            $this->resetPage();
            $this->dispatch('success-message', 'Hitung gaji baru berhasil dilakukan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formTgl = false;
        $this->resetErrorBag();
    }

    public function previewSlip($id)
    {
        $this->selectedGaji = Penggajian::with('tutor')->find($id);
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->selectedGaji = null;
    }
}
