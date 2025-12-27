<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penggajian</title>
    <style>
        body { font-family: sans-serif; }
        /* Layout Kertas */
        @page { size: A4 portrait; margin: 20px; }
        
        /* Styling Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; font-size: 11px; }
        th { background-color: #f0f0f0; text-align: center; }
        
        /* Helper Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Kop Surat */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        
        /* Tanda Tangan (Pakai Table biar aman di PDF) */
        .ttd-table { border: none; margin-top: 40px; }
        .ttd-table td { border: none; text-align: center; vertical-align: top; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0;">LAPORAN REKAPITULASI HONORARIUM TUTOR</h2>
        <h3 style="margin:5px 0;">GALLANT TUTORING CENTER</h3>
        <p style="margin:0;">Periode: {{ $bulan }} {{ $tahun }}</p>
    </div>

    <p style="font-size: 8;">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tutor</th>
                <th>Jml Pertemuan</th=>
                <th>Total Durasi Mengajar</th>
                <th>Total Honor</th=>
            </tr>
        </thead>
        <tbody>
            @foreach($penggajians as $index => $gaji)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $gaji->tutor->nama }}</td>
                <td class="text-center">{{ $gaji->total_pertemuan }}</td>
                <td class="text-center">{{ $gaji->total_durasi }}</td>
                <td class="text-right">Rp {{ number_format($gaji->total_honor, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #ddd;">
                <td colspan="4" class="text-right font-bold">TOTAL PENGELUARAN</td>
                <td class="text-right font-bold">Rp {{ number_format($penggajians->sum('total_honor'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <table class="ttd-table">
        <tr>
            <td width="30%">
                Mengetahui,<br>Pemilik
                <br><br><br><br>
                <u>( Pemilik 1 )</u>
            </td>
            <td width="40%"></td> <td width="30%">
                Pekalongan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Admin Keuangan
                <br><br><br><br>
                <u>( {{ Auth::user()->username ?? 'Admin' }} )</u>
            </td>
        </tr>
    </table>

</body>
</html>