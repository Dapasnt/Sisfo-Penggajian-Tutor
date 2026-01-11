<div>
    <section class="section">
        <h3 class="section-header">
            Selamat datang, {{ Auth::user()->username }}!
        </h3>

        @if (Auth::user()->id_role == 9 )
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-danger">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Absensi Pending</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $presensi->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tutor Aktif</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $tutor->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Presensi Hari Ini</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $presensi_hari_ini }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Tutor</th>
                                            <th class="text-center">Foto</th>
                                            <th>Jenis Kelas</th>
                                            <th>Waktu</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($presensi as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + ($presensi->firstItem() - 1) }}
                                                </td>
                                                @if (!Auth::user()->tutor)
                                                    <td>{{ $item->tutor->nama }}</td>
                                                @endif
                                                <td class="text-center">
                                                    <a href="{{ asset('storage/' . $item->bukti_foto) }}">Lihat Bukti
                                                        Foto</a>
                                                </td>
                                                <td>{{ $item->kelas->nama }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                                                </td>
                                                <td class="text-center">
                                                    @if($item->status == 'Pending')
                                                        <div class="badge badge-warning">Pending</div>
                                                    @elseif($item->status == 'Hadir')
                                                        <div class="badge badge-success">Hadir</div>
                                                    @elseif($item->status == 'Return')
                                                        <div class="badge badge-danger">Return</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->status == 'Pending')
                                                        <div class="btn-group" role="group">
                                                            <button wire:click="detail('{{ $item->id }}')"
                                                                class="btn btn-sm btn-warning">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button wire:click="update_hadir('{{ $item->id }}')"
                                                                class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button wire:click="update_return('{{ $item->id }}')"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fas fa-xmark"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button wire:click="detail('{{ $item->id }}')"
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Data presensi sudah divalidasi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>User Aktif</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $user->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Tutor Aktif</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $tutor->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-success">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Gaji Tutor bulan ini</h4>
                            </div>
                            <div class="card-Body">
                                <h4>Rp {{ number_format($penggajian->sum('total_honor'), 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="table-success">
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th>Nama Tutor</th>
                                                <th class="text-center">Jml Pertemuan</th>
                                                <th class="text-center">Total Durasi Mengajar</th>
                                                {{-- <th>Rincian (Rp)</th> --}}
                                                <th>Total Dibayar</th>
                                                <th class="text-center">Status Pembayaran</th>
                                                <th class="text-center" width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($penggajian as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($penggajian->firstItem() - 1) }}
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

                                                    <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                                        Rp {{ number_format($item->gaji_dibayar, 0, ',', '.') }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if($item->status_pembayaran == 'LUNAS')
                                                            <div class="badge badge-success">LUNAS</div>
                                                        @else
                                                            <div class="badge badge-warning">PENDING</div>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <a class="btn btn-sm btn-info m-1" href="{{ route('admin.penggajian.list') }}">Daftar Gaji Tutor</a>
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
                                    </table>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>