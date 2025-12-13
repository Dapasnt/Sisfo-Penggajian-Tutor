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
                                        <option value="01">Januari</option>
                                        <option value="02">Februari</option>
                                        <option value="03">Maret</option>
                                        <option value="04">April</option>
                                        <option value="05">Mei</option>
                                        <option value="06">Juni</option>
                                        <option value="07">Juli</option>
                                        <option value="08">Agustus</option>
                                        <option value="09">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
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
    <button wire:click="generate" wire:loading.attr="disabled" type="button" class="btn btn-primary">
        
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
                                {{-- <div class="float-left">
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
                                <div class="clearfix mb-3"></div> --}}

                                

                                {{-- Tabel --}}
                                {{-- <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Tutor</th>
                                                <th>Periode Bulan</th>
                                                <th>Periode Tahun</th>
                                                <th>Jml Pertemuan</th>
                                                <th>Total Honor</th>
                                                <th>Status</th>
                                                <th>Gaji Bersih</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($penggajianList as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($penggajianList->firstItem() - 1) }}
                                                    </td>
                                                    <td>{{ $item->tutor->nama }}</td>
                                                    <td>{{ $item->periode_bulan }}</td>
                                                    <td>{{ $item->periode_tahun }}</td>
                                                    <td>{{ $item->total_pertemuan }}</td>
                                                    <td>{{ $item->total_honor }}</td>
                                                    <td>{{ $item->status_pembayaran }}</td>
                                                    <td>{{ $item->gaji_dibayar }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button wire:click="edit('{{ $item->id_kelas }}')"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <button wire:click="confirmDelete('{{ $item->id_kelas }}')"
                                                                class="btn btn-sm btn-danger"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div> --}}
                                <div class="table-responsive">
                                <table class="table table-striped table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Nama Tutor</th>
                                            <th class="text-center">Jml Pertemuan</th>
                                            <th>Rincian (Rp)</th>
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
                                                <div class="font-weight-bold">{{ $item->tutor->nama ?? 'Tutor Dihapus' }}</div>
                                                <div class="text-small text-muted">
                                                    {{ $item->tutor->mapel ?? '-' }}
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-info">{{ $item->total_pertemuan }} x</span>
                                            </td>

                                            <td>
                                                <div>Pokok: <b>{{ number_format($item->total_honor, 0, ',', '.') }}</b></div>
                                                
                                                @if($item->tunjangan > 0)
                                                    <div class="text-success">+ Tunjangan: {{ number_format($item->tunjangan, 0, ',', '.') }}</div>
                                                @endif
                                                
                                                @if($item->potongan > 0)
                                                    <div class="text-danger">- Potongan: {{ number_format($item->potongan, 0, ',', '.') }}</div>
                                                @endif
                                            </td>

                                            <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                                Rp {{ number_format($item->gaji_dibayar, 0, ',', '.') }}
                                            </td>

                                            <td class="text-center">
                                                @if($item->status_pembayaran == 'Lunas')
                                                    <div class="badge badge-success">Lunas</div>
                                                @else
                                                    <div class="badge badge-warning">Pending</div>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button wire:click="showDetail({{ $item->id }})" 
                                                            data-toggle="modal" data-target="#modalDetail"
                                                            class="btn btn-sm btn-info" title="Lihat Rincian Absen">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <a href="#" class="btn btn-sm btn-dark" title="Cetak Slip">
                                                        <i class="fas fa-print"></i>
                                                    </a>

                                                    <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning" title="Edit Nominal">
                                                        <i class="fas fa-pencil-alt"></i>
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
</div>