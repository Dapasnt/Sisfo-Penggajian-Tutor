<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        
        .rincian-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .rincian-table th, .rincian-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .rincian-table th { background-color: #f0f0f0; }
        
        .total-row { font-weight: bold; background-color: #eee; }
        .text-right { text-align: right; }
        
        .footer { margin-top: 40px; text-align: right; }
        .ttd { height: 80px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>GALLANT TUTORING CENTER</h1>
        <p>Kota Pekalongan</p>
        <p>Telp: 0812-xxxx-xxxx</p>
    </div>

    <h3 style="text-align: center;">SLIP GAJI TUTOR</h3>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Tutor</td>
            <td width="2%">:</td>
            <td><strong>{{ $tutor->nama }}</strong></td>
            <td width="15%">Periode</td>
            <td width="2%">:</td>
            <td>{{ $bulan }} {{ $tahun }}</td>
        </tr>
        <tr>
            <td>ID Tutor</td>
            <td>:</td>
            <td>{{ $tutor->id }}</td> <td>Status</td>
            <td>:</td>
            <td style="color: green; font-weight: bold;">LUNAS</td>
        </tr>
    </table>

    <table class="rincian-table">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right">Jumlah / Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Pertemuan Mengajar</td>
                <td class="text-right">{{ $gaji->total_pertemuan }} sesi</td>
            </tr>
            <tr>
                <td>Total Durasi Mengajar (Jika ada)</td>
                <td class="text-right">{{ $gaji->total_durasi ?? '-' }} menit</td>
            </tr>
            <tr>
                <td>Gaji Pokok / Honor Mengajar</td>
                <td class="text-right">Rp {{ number_format($gaji->total_honor, 0, ',', '.') }}</td>
            </tr>
            
            {{-- Jika ada tambahan atau potongan --}}
            {{-- @if(isset($gaji->bonus) && $gaji->bonus > 0)
            <tr>
                <td>Bonus / Insentif</td>
                <td class="text-right">Rp {{ number_format($gaji->bonus, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if(isset($gaji->potongan) && $gaji->potongan > 0)
            <tr>
                <td>Potongan</td>
                <td class="text-right" style="color: red;">- Rp {{ number_format($gaji->potongan, 0, ',', '.') }}</td>
            </tr>
            @endif --}}
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL DITERIMA (TAKE HOME PAY)</td>
                <td class="text-right">Rp {{ number_format($gaji->gaji_dibayar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Pekalongan, {{ $tanggal_cetak }}</p>
        <p>Bagian Keuangan</p>
        <div class="ttd"></div> <p>( _______________________ )</p>
    </div>

</body>
</html>