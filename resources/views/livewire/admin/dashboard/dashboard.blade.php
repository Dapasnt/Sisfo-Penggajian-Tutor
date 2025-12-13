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
                                <h4>Absensi Pending</h4>
                            </div>
                            <div class="card-Body">
                                <h4>103</h4>
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
                                <h4>Total Pertemuan</h4>
                            </div>
                            <div class="card-Body">
                                <h4>103</h4>
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
                                <h4>Total Tutor</h4>
                            </div>
                            <div class="card-Body">
                                <h4>103</h4>
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
                                        <th>ID Kelas</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Tarif</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse ($kelasList as $item) --}}
                                    <tr>
                                        {{-- <td class="text-center">{{ $loop->iteration + ($tutorList->firstItem() - 1) }}</td> --}}
                                        <td>Field 1</td>
                                        <td>Field 2</td>
                                        <td>Field 3</td>
                                        <td>Field 4</td>
                                        <td>
                                            {{-- <div class="btn-group" role="group">
                                                <button wire:click="edit('{{ $item->id_kelas }}')" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
                                                <button wire:click="confirmDelete('{{ $item->id_kelas }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </div> --}}
                                            
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                                <button class="btn btn-sm btn-danger"><i class="fas fa-xmark"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- @empty --}}
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                    </tr>
                                    {{-- @endforelse --}}
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
