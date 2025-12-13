<div>

    @if ($formAdd || $formEdit)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ $formAdd ? 'Tambah Tutor' : 'Edit Tutor' }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Tutor</h4>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="{{ $formAdd ? 'add' : 'update' }}" autocomplete="off">
                                    <div class="alert alert-info">
                                        Informasi Akun Login
                                    </div>
                                    <!-- Username -->
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Username</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" wire:model.defer="username">
                                            @error('username') <div class="text-danger">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" wire:model.defer="email">
                                            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group row mb-2">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                                        <div class="col-sm-12 col-md-7">
                                            <p class="text-muted">Password default: <code>12345678</code></p>
                                        </div>
                                    </div>

                                    <div class="alert alert-primary">
                                        Biodata Tutor
                                    </div>


                                    <!-- Nama -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama
                                            Tutor</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" wire:model.defer="nama">
                                            @error('nama') <div class="text-danger">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- Mapel -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Mata
                                            Pelajaran</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input class="form-control" wire:model.defer="mapel" rows="5"></input>
                                            @error('mapel') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Jenis
                                            Kelamin</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select class="form-control selectric" wire:model.defer="jns_kel">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                <option value="Laki-laki">Laki -Laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                            @error('jns_kel') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <!-- Nomor Hp -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nomor
                                            Hp</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input class="form-control" wire:model.defer="no_hp" rows="5"></input>
                                            @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <!-- Tombol -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 col-md-7">
                                            <button wire:loading.remove wire:target="add,update" type="submit"
                                                class="btn btn-primary">
                                                {{ $formAdd ? 'Tambah' : 'Update' }}
                                            </button>
                                            <button wire:loading wire:target="add,update" class="btn btn-primary">
                                                Loading ...
                                            </button>
                                            <button wire:click="resetForm" type="button"
                                                class="btn btn-secondary">Kembali</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (!$formAdd && !$formEdit)
        <section class="section">
            <div class="section-header">
                <h1>Data Tutor</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start">
                                <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">List Tutor</h4>
                                    <button wire:click="$set('formAdd', true)" class="btn btn-primary rounded-lg">Tambah
                                        Data</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="float-right">
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

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Nama</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Nomor HP</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tutorList as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($tutorList->firstItem() - 1) }}
                                                    </td>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>{{ $item->mapel }}</td>
                                                    <td>{{ $item->jns_kel }}</td>
                                                    <td>{{ $item->no_hp }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button wire:click="edit({{ $item->id }})"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-pencil-alt"></i></button>
                                                            <button wire:click="confirmDelete({{ $item->id }})"
                                                                class="btn btn-sm btn-danger"><i
                                                                    class="fas fa-trash"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3">
                                    {{ $tutorList->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if ($confirmingDelete)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus tutor ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="deleteConfirmed" class="btn btn-danger">Ya, Hapus</button>
                        <button wire:click="$set('confirmingDelete', false)" class="btn btn-secondary">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>