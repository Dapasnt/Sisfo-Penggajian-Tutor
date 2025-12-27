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
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
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

    @if (!$formTgl)
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Data Penggajian Periode: {{ $bulan }}/{{ $tahun }}</h4>
                            <button wire:click="downloadPdf" class="btn btn-danger btn-lg">
                                <i class="fas fa-file-pdf"></i>
                                Download PDF
                                <span wire:loading wire:target="downloadPdf" class="spinner-border spinner-border-sm"
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
                                            <th class="text-right">Total Honor</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Tanggal Input</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penggajians as $index => $gaji)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $gaji->tutor->nama }}</td>
                                                <td class="text-center">{{ $gaji->total_pertemuan }}</td>
                                                <td class="text-right">Rp {{ number_format($gaji->total_honor, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-success">Lunas</span>
                                                </td>
                                                <td class="text-center">{{ $gaji->created_at->format('d-m-Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
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
                                            <td colspan="3" class="text-right">TOTAL PENGELUARAN GAJI:</td>
                                            <td class="text-right" style="font-size: 16px;">Rp
                                                {{ number_format($penggajians->sum('total_honor'), 0, ',', '.') }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>