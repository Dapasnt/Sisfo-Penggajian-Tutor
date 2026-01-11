<?php

namespace App\Livewire\Admin\Laporan;

use Livewire\Component;
use App\Models\Penggajian;
use App\Models\Pertemuan;
use App\Models\Kelas;
use App\Models\Durasi;
use App\Models\Jenjang;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class LaporanGaji extends Component
{
    use WithPagination;

    public $formTgl = false, $confirmingDelete = false;
    public $bulan, $tahun;

    // Properti untuk Modal Print
    public $showPrintPreview = false;
    public $printData = [];

    public function mount()
    {
        $this->bulan = (int) date('m');
        $this->tahun = date('Y');
    }

    // Fungsi untuk menampilkan Modal Print
    public function printReport()
    {
        $query = Penggajian::with('tutor');

        if ($this->bulan) {
            $query->where('periode_bulan', $this->bulan);
        }
        if ($this->tahun) {
            $query->where('periode_tahun', $this->tahun);
        }

        $data = $query->latest()->get();

        // 2. Siapkan Nama Bulan (Untuk Judul)
        $namaBulan = \Carbon\Carbon::create()
            ->month((int) $this->bulan)
            ->translatedFormat('F');
            
        // 3. Load View PDF
        $this->printData =  [
            'penggajians' => $data,
            'bulan' => $namaBulan,
            'tahun' => $this->tahun,
        ];
        // Validasi opsional: Pastikan data ada sebelum membuka modal
        // Tapi membiarkan modal terbuka dengan data kosong juga tidak apa-apa (informatif)
        $this->showPrintPreview = true;
    }

    // Fungsi untuk menutup Modal Print
    public function closePrintPreview()
    {
        $this->showPrintPreview = false;
    }

    public function downloadPdf()
    {
        // 1. Ambil Data (Copy logic filternya)
        $query = Penggajian::with('tutor');

        if ($this->bulan) {
            $query->where('periode_bulan', $this->bulan);
        }
        if ($this->tahun) {
            $query->where('periode_tahun', $this->tahun);
        }

        $data = $query->latest()->get();


        $namaBulan = \Carbon\Carbon::create()
            ->month((int) $this->bulan)
            ->translatedFormat('F');

        // 3. Load View PDF
        $pdf = Pdf::loadView('admin.pdf.laporan-gaji', [
            'penggajians' => $data,
            'bulan' => $namaBulan,
            'tahun' => $this->tahun
        ]);

        // 4. Download Langsung
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan-Gaji-' . $namaBulan . '-' . $this->tahun . '.pdf');
    }

    public function generate()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        try {
            $lapGaji = Penggajian::with('tutor', 'kelas', 'jenjang', 'durasi', 'pertemuan')
                ->where('periode_bulan', $this->bulan)
                ->where('periode_tahun', $this->tahun)
                ->get();

            $this->dispatch('success-message', 'Filter berhasil diterapkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }


        // Reset tampilan ke mode list
        $this->formTgl = false;
        $this->resetPage();
    }

    public function render()
    {
        $query = Penggajian::with('tutor', 'kelas', 'jenjang', 'durasi', 'pertemuan');
        if ($this->bulan) {
            $query->where('periode_bulan', $this->bulan);
        }
        if ($this->tahun) {
            $query->where('periode_tahun', $this->tahun);
        }
        $penggajians = $query->latest()->get();

        return view('livewire.admin.laporan.laporan-gaji', [
            'penggajians' => $penggajians
        ]);
    }

    public function resetForm()
    {
        $this->formTgl = false;
        $this->resetErrorBag();
    }
}
