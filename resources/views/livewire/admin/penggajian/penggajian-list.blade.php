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
                                    <button wire:loading.remove wire:target="generate" wire:click="$set('formTgl', false)"
                                        type="submit" class="btn btn-primary">Tampilkan
                                    </button>
                                    <button wire:loading wire:target="generate" class="btn btn-primary">
                                        Loading ...
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start">
                                <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">List Penggajian</h4>
                                </div>
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


                                {{-- Pemilihan periode --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <button wire:click="$set('formTgl', true)" class="btn btn-primary rounded-lg">
                                                Pilih Periode
                                            </button>
                                        </div>
                                        <h6>Daftar Gaji Tutor Periode {{ $bulan }} {{ $tahun }}</h6>
                                    </div>
                                </div>
                                

                                {{-- Tabel --}}
                                <div class="table-responsive">
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
                                                            {{-- <button wire:click="edit({{ $item->id_kelas }})"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-pencil-alt"></i></button> --}}
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