<div>
    <section class="section">
        <h3 class="section-header">
            Selamat datang, {{ Auth::user()->username }}!
        </h3>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pertemuan Terlaksana Bulan Ini</h4>
                            </div>
                            <div class="card-Body">
                                <h4>{{ $jumlah_hadir }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Estimasi Gaji Bulan ini</h4>
                            </div>
                            <div class="card-Body">
                                <h4>Rp {{ number_format($estimasi_gaji, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Status Presensi Terakhir</h4>
                            </div>
                            <div class="card-body align-items-center" style="height: 60px;">
                                <span class="badge badge-{{ $warna_status }} py-2 px-3" style="font-size: 14px;">
                                    {{ $status_terakhir }}
                                </span>
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
                                                <th class="text-center">#</th>
                                                <th class="text-center">Foto</th>
                                                <th>Jenis Kelas</th>
                                                <th>Waktu</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">
                                                    @if (!Auth::user()->tutor)
                                                        Action
                                                    @else
                                                        Detail
                                                    @endif
                                                </th>
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
                                                        <a href="{{ asset('storage/' . $item->bukti_foto) }}">Lihat Bukti Foto</a>
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
                                                        <a class="btn btn-sm btn-info m-1" href="{{ route('admin.presensi.list') }}">Ke Halaman Presensi</a>
                                                            {{-- @if ($item->status == 'Return')
                                                                <button wire:click="retake('{{ $item->id }}')"
                                                                    class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-sync mr-1"></i>Retake
                                                                </button>
                                                            @else
                                                                <button wire:click="detail('{{ $item->id }}')"
                                                                    class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endif --}}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Belum ada data presensi</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3">
                                    {{ $presensi->links() }}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
