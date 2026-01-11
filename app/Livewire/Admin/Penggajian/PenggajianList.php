<?php

namespace App\Livewire\Admin\Penggajian;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Xendit\Configuration;
use Xendit\Disbursement\DisbursementApi;
use Xendit\Disbursement\CreateDisbursementRequest;
use Illuminate\Support\Str;
use Xendit\Payout\CreatePayoutRequest;
use Xendit\Payout\PayoutApi;

#[Title('GTC | Penggajian')]
class PenggajianList extends Component
{

    use WithPagination;
    public $formTgl = false, $confirmingDelete = false, $showPreviewModal = false, $formDetail = false;
    public $search = '';
    public $isFiltered = true;

    public $id_tutor, $nama, $bulan, $tahun, $jmlKelas, $selectedGaji, $detailData, $rincianGaji;

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
        $penggajianList = [];
        $totalTutor = 0;
        $totalNominal = 0;

        if ($this->isFiltered) {
            // Base Query
            $query = Penggajian::with(['tutor'])
                ->where('periode_bulan', $this->bulan)
                ->where('periode_tahun', $this->tahun)
                ->search($this->search);

            // Hitung Statistik (Total Tutor & Nominal) sebelum dipagination
            // Clone query biar ga ngerusak query utama
            $statQuery = clone $query;
            $dataStat = $statQuery->where('status_pembayaran', 'PENDING')->get();

            $totalTutor = $dataStat->count();
            $totalNominal = $dataStat->sum('total_honor');

            $penggajianList = $query->paginate(10);
        }

        return view('livewire.admin.penggajian.penggajian-list', compact('penggajianList', 'totalTutor', 'totalNominal'));
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
                    throw new \Exception("Tidak ada data pertemuan untuk periode ini.");
                }

                $grouped = $listPertemuan->groupBy('id_tutor');

                foreach ($grouped as $idTutor => $absenTutor) {
                    //Hitung Total Honor dan Jumlah Pertemuan
                    $totalHonor = $absenTutor->sum('tarif_saat_itu');
                    $jumlahPertemuan = $absenTutor->count();
                    $totalDurasi = $absenTutor->sum(function ($item) {
                        return $item->durasi->durasi ?? 0;
                    });
                    // throw new \Exception("Simulasi Error Rollback Jalur 6");
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

    public function lihatGaji()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        try {
            $this->isFiltered = true;
            $this->formTgl = false;
            $this->resetPage();
            $this->dispatch('success-message', 'Data penggajian berhasil ditampilkan.');
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

        // Added this 30 des/ 11:17
        $riwayatMengajar = Pertemuan::with(['kelas', 'jenjang', 'durasi'])
            ->where('id_tutor', $this->selectedGaji->id_tutor)
            ->whereMonth('tgl_pertemuan', $this->selectedGaji->periode_bulan) // Sesuaikan nama kolom tgl_pertemuan kamu
            ->whereYear('tgl_pertemuan', $this->selectedGaji->periode_tahun)
            ->get();

        // 2. Lakukan GROUPING (Pengelompokan)
        // Kita kelompokkan berdasarkan "Tingkat" dan "Durasi" agar mirip gambar
        $this->rincianGaji = $riwayatMengajar->groupBy(function ($item) {
            // KITA GABUNGKAN 3 KOLOM JADI SATU KEY UNIK
            // Contoh hasil key: "Private Class-SD-90"
            return $item->jenis_kelas . '-' . $item->jenjang . '-' . $item->durasi;
        })->map(function ($group) {
            // Ambil satu data sampel dari grup untuk ambil info nama/labelnya
            $contoh = $group->first();

            return [
                // Pastikan nama properti ini ('jenis_kelas', 'jenjang') SAMA dengan nama kolom di database kamu
                'jenis_kelas' => $contoh->kelas->nama,
                'jenjang'     => $contoh->jenjang->nama,
                'durasi'      => $contoh->durasi->durasi,

                // Hitung-hitungan
                'tarif'            => $contoh->tarif_saat_itu,
                'jumlah_pertemuan' => $group->count(),
                'subtotal'         => $group->sum('tarif_saat_itu')
            ];
        })->values();
        // to this


        $this->showPreviewModal = true;
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->selectedGaji = null;
    }

    public function bayarGaji($idPenggajian)
    {

        $gaji = Penggajian::with('tutor')->find($idPenggajian);

        if (!$gaji || $gaji->status_transfer == 'COMPLETED') {
            session()->flash('error', 'Gaji tidak valid.');
            return;
        }

        $secretKey = env('XENDIT_SECRET_KEY');
        $external_id = 'GAJI-' . $gaji->id_penggajian . '-' . time();

        $response = Http::withBasicAuth($secretKey, '')
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

        //Cek hasil
        if ($response->successful()) {
            $data = $response->json();

            // Simpan respons sukses ke database
            $gaji->update([
                'xendit_id' => $data['id'],
                'xendit_external_id' => $external_id,
                'status_transfer' => $data['status'],
                'status_pembayaran' => 'PENDING'
            ]);

            session()->flash('success', 'Berhasil! Transfer sedang diproses bank.');
        } else {
            // Jika Gagal (Misal: Saldo kurang, Bank Code salah)
            $errorData = $response->json();
            $pesanError = $errorData['message'] ?? 'Gagal menghubungi Xendit';

            // Simpan info error jika perlu
            $gaji->update(['failure_code' => $errorData['code'] ?? 'ERROR']);

            session()->flash('error', 'Gagal Transfer: ' . $pesanError);

            // dd([
            //     'Status Code' => $response->status(),
            //     'Body' => $response->body(), // Ini pesan lengkap dari Xendit
            //     'JSON' => $response->json()
            // ]);
        }
    }

    public function bayarSemua()
    {
        // Set API Key
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

        // Instance API
        $apiInstance = new PayoutApi();

        $listPending = Penggajian::where('status_pembayaran', 'PENDING')
            ->with('tutor')
            ->get();

        if ($listPending->isEmpty()) {
            session()->flash('error', 'Tidak ada antrian gaji pending.');
            return;
        }

        $sukses = 0;
        $gagal = 0;

        foreach ($listPending as $gaji) {
            try {
                // 1. Format Kode Bank (Wajib 'ID_BANK' di Payout V3 / SDK v7)
                // Contoh: 'BCA' -> 'ID_BCA'
                $rawBank = strtoupper(trim($gaji->tutor->bank_code));
                $channelCode = str_starts_with($rawBank, 'ID_') ? $rawBank : 'ID_' . $rawBank;

                // 2. Siapkan Object Request (WAJIB OBJECT)
                $payoutRequest = new CreatePayoutRequest([
                    'reference_id' => 'GAJI-' . $gaji->id_penggajian . '-' . time(),
                    'currency'     => 'IDR',
                    'channel_code' => $channelCode,
                    'channel_category' => 'BANK',
                    'channel_properties' => [
                        'account_holder_name' => $gaji->tutor->account_holder_name,
                        'account_number'      => $gaji->tutor->account_number,
                    ],
                    'amount'       => (float) $gaji->total_honor, // SDK v7 minta float/int
                    'description'  => 'Gaji Periode ' . (string) $gaji->periode_bulan,
                    'type'         => 'DIRECT_DISBURSEMENT'
                ]);

                // 3. Idempotency Key
                $idempotencyKey = 'payout-' . $gaji->id_penggajian . '-' . time();

                // 4. Eksekusi (Perhatikan urutan parameter v7)
                // ($idempotency_key, $for_user_id, $create_payout_request)
                $result = $apiInstance->createPayout($idempotencyKey, null, $payoutRequest);

                // 5. Simpan Hasil
                $gaji->update([
                    'status_pembayaran' => 'PROSES',
                    'xendit_id'         => $result->getId(),
                    'xendit_external_id' => $result->getReferenceId(),
                ]);

                $sukses++;
            } catch (\Xendit\XenditSdkException $e) {
                $gagal++;
                // Log Error Lengkap dari Xendit
                Log::error("Xendit Error ID {$gaji->id_penggajian}: " . json_encode($e->getFullError()));
            } catch (\Exception $e) {
                $gagal++;
                // Log Error System
                Log::error("System Error ID {$gaji->id_penggajian}: " . $e->getMessage());
            }
        }

        session()->flash('message', "Selesai! Sukses: $sukses, Gagal: $gagal.");
    }

    public function resetForm()
    {
        $this->formTgl = false;
        $this->formDetail = false;
        $this->resetErrorBag();
    }
}
