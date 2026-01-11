<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PenggajianTutor extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Filter
    public $bulan;
    public $tahun;
    public $search;

    // State Modals (JANGAN DIKOMENTARI!)
    public $detailData = null; // WAJIB ADA & PUBLIC
    public $selectedGaji = null;
    public $rincianGaji = []; 
    public $showDetail = false;
    public $showPreviewModal = false;

    public function mount()
    {
        $this->bulan = (int) date('m');
        $this->tahun = (int) date('Y');
    }

    public function render()
    {
        $tutorId = Auth::user()->tutor->id;

        $riwayat = Penggajian::with(['tutor'])
            ->where('id_tutor', $tutorId)
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->paginate(10);

        return view('livewire.admin.penggajian.penggajian-tutor', [
            'riwayat' => $riwayat
        ]);
    }

    // --- FITUR DETAIL (SUDAH DIPERBAIKI) ---
    public function formDetail($id)
    {
        // 1. Ambil data
        $data = Penggajian::where('id_penggajian', $id)
            ->where('id_tutor', Auth::user()->tutor->id)
            ->with(['pertemuan.kelas', 'pertemuan.jenjang', 'pertemuan.durasi'])
            ->firstOrFail();
            
        // 2. Simpan ke properti public agar bisa dibaca di View
        $this->detailData = $data;
        
        // 3. Tampilkan modal/section detail
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->detailData = null; // Reset data agar hemat memori
    }

    // --- FITUR SLIP GAJI ---
    public function previewSlip($id)
    {
        $this->selectedGaji = Penggajian::where('id_penggajian', $id)
            ->where('id_tutor', Auth::user()->tutor->id)
            ->with(['tutor', 'pertemuan.kelas', 'pertemuan.jenjang'])
            ->firstOrFail();

        $grouped = [];
        foreach ($this->selectedGaji->pertemuan as $p) {
            $key = $p->id_kelas . '-' . $p->id_jenjang . '-' . $p->durasi->durasi;
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'jenis_kelas' => $p->kelas->nama,
                    'jenjang' => $p->jenjang->nama,
                    'durasi' => $p->durasi->durasi . ' Menit',
                    'tarif' => $p->tarif_saat_itu,
                    'jumlah_pertemuan' => 0,
                    'subtotal' => 0
                ];
            }
            $grouped[$key]['jumlah_pertemuan']++;
            $grouped[$key]['subtotal'] += $p->tarif_saat_itu;
        }
        
        $this->rincianGaji = array_values($grouped);
        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->selectedGaji = null;
    }
}