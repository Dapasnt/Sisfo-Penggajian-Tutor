<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Pertemuan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakSlip extends Controller
{
    public function cetakSlip($id)
    {
        // $gaji = Penggajian::with(['tutor'])->findOrFail($id);

        // $data = [
        //     'gaji'  => $gaji,
        //     'tutor' => $gaji->tutor,
        //     'bulan' => \Carbon\Carbon::create()->month($gaji->periode_bulan)->translatedFormat('F'),
        //     'tahun' => $gaji->periode_tahun,
        //     'tanggal_cetak' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        // ];

        // $pdf = Pdf::loadView('admin.pdf.slip_gaji', $data);

        // $pdf->setPaper('a4', 'portrait');

        // // ->stream() untuk melihat dulu, ->download() untuk langsung unduh
        // return $pdf->stream('Slip_Gaji_' . $gaji->tutor->nama . '.pdf');
        $gaji = Penggajian::with('tutor')->find($id);

        // Added this 30 des/ 11:17
        $riwayatMengajar = Pertemuan::with(['kelas','jenjang','durasi'])
            ->where('id_tutor', $gaji->id_tutor)
            ->whereMonth('tgl_pertemuan', $gaji->periode_bulan) // Sesuaikan nama kolom tgl_pertemuan kamu
            ->whereYear('tgl_pertemuan', $gaji->periode_tahun)
            ->get();

        // 2. Lakukan GROUPING (Pengelompokan)
        // Kita kelompokkan berdasarkan "Tingkat" dan "Durasi" agar mirip gambar
        $rincianGaji = $riwayatMengajar->groupBy(function ($item) {
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
        });
        $pdf = Pdf::loadView('admin.pdf.slip_gaji', [
        'gaji' => $gaji,
        'bulan' => \Carbon\Carbon::create()->month($gaji->periode_bulan)->translatedFormat('F'),
        'tahun' => $gaji->periode_tahun,
        'tanggal_cetak' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        'rincian' => $rincianGaji // <--- Variabel baru ini yang penting
        ]);

        return $pdf->stream('Slip_Gaji_' . $gaji->tutor->nama . '.pdf');
    }
}