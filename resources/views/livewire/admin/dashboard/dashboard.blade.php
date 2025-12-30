<div>
    <section class="section">
        <h3 class="section-header">
            Selamat datang, {{ Auth::user()->username }}!
        </h3>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon shadow-primary bg-danger">
                            <i class="fas fa-users"></i>
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
    </section>
</div>