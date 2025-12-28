<div>
    @if($formTgl)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Pilih Periode</h1>
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

    @if($formDetail && $detailData)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Detail Penggajian</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Pertemuan</h4>
                            </div>
                            <div class="card-header">
                                <h6>Nama Tutor: {{ $detailData->tutor->nama ?? 'Tutor Dihapus' }}</h6>
                            </div>
                            

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                {{-- <th class="text-center" width="5%">No</th> --}}
                                                <th>Tgl Pertemuan</th>
                                                <th class="text-center">Jenis Kelas yang diajar</th>
                                                <th class="text-center">Jenjang</th>
                                                <th class="text-center">Durasi Mengajar</th>
                                                <th>Tarif Saat itu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailData->pertemuan as $detail)
                                                <tr>
                                                    {{-- <td class="text-center">
                                                        {{ $loop->iteration + ($detail->firstItem() - 1) }}
                                                    </td> --}}

                                                    <td>
                                                        <div class="font-weight-bold">
                                                            {{ \Carbon\Carbon::parse($detail->tgl_pertemuan)->translatedFormat('d-M-Y') }}
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <span>{{ $detail->kelas->nama }}</span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span>{{ $detail->jenjang->nama }}</span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span>{{ $detail->durasi->durasi }} Menit</span>
                                                    </td>

                                                    <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                                        Rp {{ number_format($detail->tarif_saat_itu, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right font-weight-bold text-primary">TOTAL GAJI</td>
                                                <td class="font-weight-bold text-primary">Rp
                                                    {{ number_format($detailData->total_honor, 0, ',', '.') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="text-right">
                                    <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(!$formTgl && !$showPreviewModal && !$formDetail)
        <section class="section">
            <div class="section-header">
                <h1>Data Penggajian</h1>
            </div>

            <div class="section-body">

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    List Penggajian
                                    <span class="badge badge-primary">{{ $bulan }}/{{ $tahun }}</span>
                                </h4>

                                {{-- Tombol Pemilihan periode --}}
                                <button wire:click="$set('formTgl', true)" class="btn btn-warning rounded-lg">
                                    <i class="fas fa-calculator"></i> Hitung Gaji Baru
                                </button>
                            </div>
                            <div class="card-body">

                                {{-- Search Bar --}}
                                <div class="float-left">
                                    <div class="d-flex jutstify-content-end">
                                        <div class="input-group">
                                            <input wire:model.live.debounce.500ms="search" type="text" class="form-control"
                                                placeholder="Cari ...">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix mb-3"></div>



                                {{-- Tabel --}}
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th>Nama Tutor</th>
                                                <th class="text-center">Jml Pertemuan</th>
                                                <th class="text-center">Total Durasi Mengajar</th>
                                                {{-- <th>Rincian (Rp)</th> --}}
                                                <th>Total Dibayar</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center" width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($penggajianList as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($penggajianList->firstItem() - 1) }}
                                                    </td>

                                                    <td>
                                                        <div class="font-weight-bold">
                                                            {{ $item->tutor->nama ?? 'Tutor Dihapus' }}
                                                        </div>
                                                        <div class="text-small text-muted">
                                                            {{ $item->tutor->mapel ?? '-' }}
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="badge badge-info">{{ $item->total_pertemuan }} x</span>
                                                    </td>

                                                    <td class="text-center">
                                                        <span>{{ $item->total_durasi }} Menit</span>
                                                    </td>

                                                    {{-- <td>
                                                        <div><b>{{ number_format($item->total_honor, 0, ',', '.') }}</b></div>

                                                        @if($item->tunjangan > 0)
                                                        <div class="text-success">+ Tunjangan: {{
                                                            number_format($item->tunjangan, 0, ',', '.') }}</div>
                                                        @endif

                                                        @if($item->potongan > 0)
                                                        <div class="text-danger">- Potongan: {{ number_format($item->potongan,
                                                            0, ',', '.') }}</div>
                                                        @endif
                                                    </td> --}}

                                                    <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                                        Rp {{ number_format($item->gaji_dibayar, 0, ',', '.') }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if($item->status_pembayaran == 'Lunas')
                                                            <div class="badge badge-success">Lunas</div>
                                                            <div class="text-danger">{{ Carbon\Carbon::parse($item->tgl_dibayar)->translatedFormat('d-M-Y') }}</div>
                                                        @else
                                                            <div class="badge badge-warning">Pending</div>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button wire:click="detail({{ $item->id_penggajian }})" {{--
                                                                data-toggle="modal" data-target="#modalDetail" --}}
                                                                class="btn btn-sm btn-info" title="Lihat Rincian Absen">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button wire:click="previewSlip({{ $item->id_penggajian }})"
                                                                class="btn btn-dark btn-sm" title="Cetak Slip">
                                                                <i class="fas fa-print"></i>
                                                            </button>
                                                            <button wire:click="bayarGaji({{ $item->id_penggajian }})"
                                                                class="btn btn-dark btn-sm" title="Bayar Gaji">
                                                                <i class="fas fa-dollar-sign"></i>
                                                            </button>
                                                        </div>
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
                                                            <p class="lead">
                                                                Silakan klik tombol "Hitung Gaji Baru" di atas.
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="p-3">
                                {{ $penggajianList->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($showPreviewModal && $selectedGaji)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Preview Slip Gaji</h5>
                        <button type="button" class="close" wire:click="closePreview" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="print-area" class="p-4" style="background: white;">
                            <div class="text-center mb-2">
                                <h4 class="text-2xl font-bold uppercase tracking-wide">GALLANT TUTORING CENTER</h4>
                                <p class="text-sm">Kota Pekalongan</p>
                                <p class="text-sm">Telp: 0812-xxxx-xxxx</p>
                            </div>

                            <div class="border-b-2 border-black mb-6"></div>

                            <div class="text-center mb-8">
                                <h4 class="text-xl font-bold uppercase">SLIP GAJI TUTOR</h4>
                            </div>

                            <div class="flex justify-between mb-6 text-sm">
                                <table class="w-1/2">
                                    <tr>
                                        <td class="pb-2 w-32">Nama Tutor</td>
                                        <td class="pb-2 font-bold">: {{ $selectedGaji->tutor->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2">ID Tutor</td>
                                        <td class="pb-2">: {{ $selectedGaji->tutor->id }}</td>
                                    </tr>
                                </table>

                                <table class="w-1/3">
                                    <tr>
                                        <td class="pb-2 w-24">Periode</td>
                                        <td class="pb-2">:
                                            {{ \Carbon\Carbon::parse($selectedGaji->periode)->translatedFormat('F Y') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pb-2">Status</td>
                                        <td class="pb-2 text-green-600 font-bold uppercase">: LUNAS</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mb-12">
                                <table class="w-full border border-black text-sm">
                                    <thead>
                                        <tr class="bg-gray-200">
                                            <th class="border border-black px-3 py-2 text-left font-bold w-3/4">Keterangan
                                            </th>
                                            <th class="border border-black px-3 py-2 text-left font-bold">Jumlah / Nilai
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-black px-3 py-2">Total Pertemuan Mengajar</td>
                                            <td class="border border-black px-3 py-2">{{ $selectedGaji->total_pertemuan }}
                                                sesi
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black px-3 py-2">Total Durasi Mengajar</td>
                                            <td class="border border-black px-3 py-2">{{ $selectedGaji->total_durasi }}
                                                menit
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black px-3 py-2">Gaji Pokok / Honor Mengajar</td>
                                            <td class="border border-black px-3 py-2">Rp
                                                {{ number_format($selectedGaji->total_honor ?? 56000, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr class="bg-gray-200 font-bold">
                                            <td class="border border-black px-3 py-2 uppercase">TOTAL DITERIMA (TAKE HOME
                                                PAY)
                                            </td>
                                            <td class="border border-black px-3 py-2">Rp
                                                {{ number_format($selectedGaji->gaji_dibayar ?? 56000, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end text-sm">
                                <div class="text-center w-64">
                                    <p class="mb-1">Pekalongan, {{ now()->translatedFormat('d F Y') }}</p>
                                    <p class="mb-12">Pemilik</p>
                                    <br><br>
                                    <p>( Pemilik GTC )</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if($selectedGaji)
                            <a href="{{ route('admin.penggajian.cetak', $selectedGaji->id_penggajian) }}" target="_blank"
                                class="btn btn-primary mx-2">
                                <i class="fa fa-file-pdf mr-2"></i> Cetak Slip
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>