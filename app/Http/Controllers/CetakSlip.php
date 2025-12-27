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
        // 1. Ambil data penggajian beserta relasi tutor & jadwal (jarivis)
        $gaji = Penggajian::with(['tutor'])->findOrFail($id);

        // 2. Siapkan data untuk dikirim ke view PDF
        $data = [
            'gaji'  => $gaji,
            'tutor' => $gaji->tutor,
            'bulan' => \Carbon\Carbon::create()->month($gaji->periode_bulan)->translatedFormat('F'),
            'tahun' => $gaji->periode_tahun,
            // Opsional: Generate nomor surat otomatis atau info admin
            'tanggal_cetak' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        ];

        // 3. Load view PDF
        // 'pdf.slip_gaji' adalah nama file blade yang akan kita buat
        $pdf = Pdf::loadView('admin.pdf.slip_gaji', $data);

        // 4. Atur ukuran kertas (opsional)
        $pdf->setPaper('a4', 'portrait'); // atau 'a5', 'landscape'

        // 5. Download atau Stream (tampil di browser)
        // ->stream() untuk melihat dulu, ->download() untuk langsung unduh
        return $pdf->stream('Slip_Gaji_' . $gaji->tutor->nama . '.pdf');
    }
}