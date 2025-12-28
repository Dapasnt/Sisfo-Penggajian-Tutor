<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Xendit\Configuration;
use Xendit\Disbursement\DisbursementApi;
use Xendit\Disbursement\CreateDisbursementRequest;
use Illuminate\Support\Str;

#[Title('GTC | Penggajian')]
class PenggajianList extends Component
{

    use WithPagination;
    public $formTgl = false, $confirmingDelete = false, $showPreviewModal = false, $formDetail = false;
    public $search = '';

    public $id_tutor, $nama, $bulan, $tahun, $jmlKelas, $selectedGaji, $detailData;

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
                    //Hitung Total Honor dan Jumlah Pertemuan
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

    public function detail($id)
    {
        $this->resetForm();
        $this->detailData = Penggajian::with([
            'tutor',
            'pertemuan.tutor',
            'pertemuan.kelas'
        ])
            ->find($id);

        if ($this->detailData) {
            $this->formDetail = true; // Aktifkan Mode Detail
            // dd($this->detailData);
        }
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

    public function bayarGaji($idPenggajian)
    {
        // 1. Ambil Data
        $gaji = Penggajian::with('tutor')->find($idPenggajian);

        // Validasi sederhana
        if (!$gaji || $gaji->status_transfer == 'COMPLETED') {
            session()->flash('error', 'Gaji tidak valid.');
            return;
        }

        // 2. Siapkan Data Request
        $secretKey = env('XENDIT_SECRET_KEY');
        $external_id = 'GAJI-' . $gaji->id . '-' . time(); // ID Unik Transaksi

        // 3. Kirim Request Langsung ke API Xendit
        // Kita gunakan Endpoint "/disbursements" (Standard)
        $response = Http::withBasicAuth($secretKey, '') // Username=Key, Password=Kosong
            ->withHeaders([
                'Content-Type' => 'application/json',
                'X-IDEMPOTENCY-KEY' => $external_id // Mencegah double transfer
            ])
            ->post('https://api.xendit.co/disbursements', [
                'external_id' => $external_id,
                'amount' => (int) $gaji->total_honor,
                'bank_code' => $gaji->tutor->bank_code,
                'account_holder_name' => $gaji->tutor->account_holder_name,
                'account_number' => $gaji->tutor->account_number,
                'description' => 'Gaji Tutor Periode ' . $gaji->periode_bulan . '/' . $gaji->periode_tahun,
            ]);

        // 4. Cek Hasilnya
        if ($response->successful()) {
            $data = $response->json();

            // Simpan respons sukses ke database
            $gaji->update([
                'xendit_id' => $data['id'],
                'xendit_external_id' => $external_id,
                'status_transfer' => $data['status'], // Biasanya 'PENDING'
                'status_pembayaran' => 'Pending'
            ]);

            session()->flash('success', 'Berhasil! Transfer sedang diproses bank.');
        } else {
            // Jika Gagal (Misal: Saldo kurang, Bank Code salah)
            // $errorData = $response->json();
            // $pesanError = $errorData['message'] ?? 'Gagal menghubungi Xendit';

            // // Simpan info error jika perlu
            // $gaji->update(['failure_code' => $errorData['code'] ?? 'ERROR']);

            // session()->flash('error', 'Gagal Transfer: ' . $pesanError);

            dd([
                'Status Code' => $response->status(),
                'Body' => $response->body(), // Ini pesan lengkap dari Xendit
                'JSON' => $response->json()
            ]);
        }
    }

    public function resetForm()
    {
        $this->formTgl = false;
        $this->formDetail = false;
        $this->resetErrorBag();
    }
}
