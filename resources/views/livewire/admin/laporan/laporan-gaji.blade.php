<div>
    @if($formTgl)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Pilih Periode Laporan</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start">
                                <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">Pilih Periode Penggajian</h4>
                                </div>
                                <div class="d-flex gap-2 align">
                                    <h4 class="m-auto">Bulan</h4>
                                    <select wire:model.live="bulan" class="p-2 border rounded mr-3">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>

                                    <h4 class="m-auto">Tahun</h4>
                                    <select wire:model.live="tahun" class="p-2 border rounded">
                                        @for($y = 2023; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>

                            </div>
                            <div class="form-group mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:click="generate" wire:loading.attr="disabled" type="button"
                                        class="btn btn-primary">

                                        <span wire:loading.remove wire:target="generate">
                                            Tampilkan
                                        </span>

                                        <span wire:loading wire:target="generate">
                                            <i class="fas fa-spinner fa-spin"></i> Sedang Menghitung...
                                        </span>

                                    </button>

                                    <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(!$formTgl)
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Laporan Penggajian</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <button wire:click="$set('formTgl', true)" class="btn btn-warning btn-lg">
                                        <i class="fas fa-filter"></i>
                                        Filter Laporan Gaji
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Penggajian Periode: {{ $bulan }}/{{ $tahun }}</h4>
                            <button wire:click="printReport" class="btn btn-danger btn-lg">
                                <i class="fas fa-file-pdf"></i>
                                Download PDF
                                <span wire:loading wire:target="printReport" class="spinner-border spinner-border-sm"
                                    role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Nama Tutor</th>
                                            <th class="text-center">Jml. Pertemuan</th>
                                            <th class="text-center">Total Durasi Mengajar</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-right">Total Honor</th>
                                            <th class="text-center">Tanggal Dibayar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penggajians as $index => $gaji)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $gaji->tutor->nama }}</td>
                                                <td class="text-center">{{ $gaji->total_pertemuan }}</td>
                                                <td class="text-center">{{ $gaji->total_durasi }}</td>
                                                <td class="text-center">
                                                    @if($gaji->status_pembayaran == 'Lunas')
                                                        <div class="badge badge-success">Lunas</div>
                                                    @else
                                                        <div class="badge badge-warning">Pending</div>
                                                    @endif
                                                </td>
                                                <td class="text-right">Rp
                                                    {{ number_format($gaji->total_honor, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    @if($gaji->tgl_dibayar == null)
                                                        Pending
                                                    @else
                                                        {{ \Carbon\Carbon::parse($gaji->tgl_dibayar)->translatedFormat('d M Y, H:i') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon">
                                                            <i class="fas fa-folder-open"></i>
                                                        </div>
                                                        <h2>Belum ada data gaji untuk periode {{ $bulan }}-{{ $tahun }}</h2>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold" style="background-color: #f9f9f9;">
                                            <td colspan="5" class="text-right">TOTAL PENGELUARAN GAJI:</td>
                                            <td class="text-right" style="font-size: 16px;">Rp
                                                {{ number_format($penggajians->sum('total_honor'), 0, ',', '.') }}
                                            </td>
                                            {{-- <td colspan="1"></td> --}}
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($showPrintPreview)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mx-2">Preview Laporan Penggajian</h5>
                    {{-- <button type="button" class="btn btn-primary mx-2" wire:click="downloadPdf">
                        <i class="fas fa-print"></i> Cetak Sekarang
                    </button> --}}
                    <button type="button" class="close" wire:click="closePrintPreview">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="print-area" class="p-4" style="background: white;">
                        <div class="header">
                            <h2 style="margin:0;">LAPORAN REKAPITULASI HONORARIUM TUTOR</h2>
                            <h3 style="margin:5px 0;">GALLANT TUTORING CENTER</h3>
                            <p style="margin:0;">Periode: {{ $bulan }} {{ $tahun }}</p>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                                <thead>
                                    <tr style="background-color: #f2f2f2;">
                                        <th style="border: 1px solid #000; padding: 8px;">No</th>
                                        <th style="border: 1px solid #000; padding: 8px;">Nama Tutor</th>
                                        <th style="border: 1px solid #000; padding: 8px;">Jml Pertemuan</th>
                                        <th style="border: 1px solid #000; padding: 8px;">Total Durasi Mengajar</th>
                                        <th style="border: 1px solid #000; padding: 8px;">Status</th>
                                        <th style="border: 1px solid #000; padding: 8px; text-align: right;">Total Gaji
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penggajians as $index => $gaji)
                                        <tr>
                                            <td style="border: 1px solid #000; padding: 8px;" class="text-center">{{ $index + 1 }}</td>
                                            <td style="border: 1px solid #000; padding: 8px;">{{ $gaji->tutor->nama }}</td>
                                            <td style="border: 1px solid #000; padding: 8px;" class="text-center">{{ $gaji->total_pertemuan }}</td>
                                            <td style="border: 1px solid #000; padding: 8px;" class="text-center">{{ $gaji->total_durasi}}</td>
                                            <td style="border: 1px solid #000; padding: 8px;" class="text-center">{{ $gaji->status_pembayaran }}</td>
                                            <td style="border: 1px solid #000; padding: 8px;" class="text-right">Rp {{ number_format($gaji->total_honor, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #ddd;">
                                        <td colspan="5" style="border: 1px solid #000; padding: 8px;" class="text-right font-bold">TOTAL PENGELUARAN</td>
                                        <td style="border: 1px solid #000; padding: 8px;" class="text-right font-bold">Rp{{ number_format($penggajians->sum('total_honor'), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <table class="ttd-table">
                            <tr>
                                <td width="30%">
                                    Mengetahui,<br>Pemilik
                                    <br><br><br><br>
                                    <u>( Pemilik 1 )</u>
                                </td>
                                <td width="40%"></td>
                                <td width="30%">
                                    Pekalongan, {{ date('d F Y') }}<br>Admin Keuangan
                                    <br><br><br><br>
                                    <u>( {{ Auth::user()->username ?? 'Admin' }} )</u>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-primary" onclick="printReport()">
                        <i class="fas fa-print"></i> Cetak
                    </button> --}}
                    <button type="button" class="btn btn-primary mx-2" wire:click="downloadPdf">
                        <i class="fas fa-print"></i> Cetak Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            const printContents = document.getElementById('print-area').innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <html>
                <head>
                    <title>Laporan Penggajian</title>
                    <style>
                        body { font-family: Arial, sans-serif; -webkit-print-color-adjust: exact; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
                        th, td { padding: 5px; }
                        h3 { text-align: center; margin-bottom: 5px; }
                        .text-right { text-align: right; }
                        .text-center { text-align: center; }
                        @media print {
                            @page { margin: 1cm; size: A4; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContents;
            window.location.rescan();
        }
    </script>
    @endif
</div>