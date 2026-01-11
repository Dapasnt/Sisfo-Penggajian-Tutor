<div>

    @if ($formAdd || $formEdit)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1></i>{{ $formAdd ? 'Tambah User' : 'Edit User' }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form User</h4>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="{{ $formAdd ? 'add' : 'update' }}" autocomplete="off">
                                    <!-- username -->
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Username</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control" wire:model.defer="username">
                                            @error('username') <div class="text-danger">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- email -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input class="form-control" wire:model.defer="email" rows="5"></input>
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <!-- Roles -->
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Roles</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select class="form-control selectric" wire:model.defer="id_role">
                                                <option value="">-- Pilih Role --</option>
                                                @foreach ($roles as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_role') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>

                                    <!-- password -->
                                    @if ($formEdit)
                                        <div class="form-group row mb-4">
                                            <label
                                                class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                                            <div class="col-sm-12 col-md-7">
                                                <a wire:ignore wire:click="confirmResetPassword"
                                                    class="btn btn-primary text-white cursor-pointer">Reset Password</a>
                                            </div>
                                        </div>
                                    @elseif ($formAdd)
                                        <div class="form-group row mb-2">
                                            <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                            <div class="col-sm-12 col-md-7">
                                                <p class="text-muted">Password default: <code>12345678</code></p>
                                            </div>
                                        </div>
                                    @endif

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
                <h1>List User</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column align-items-start">
                                <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="mb-0">List User</h4>
                                    <button wire:click="$set('formAdd', true)" class="btn btn-primary rounded-lg"><i class="fa-solid fa-plus mr-2"></i>Tambah
                                        User</button>
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
                                        <thead class="table-success">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($userList as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($userList->firstItem() - 1) }}</td>
                                                    <td>{{ $item->username }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->role->nama }}</td>
                                                    <td class="text-center">
                                                        @if ($item->is_active == 1)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button wire:click="edit({{ $item->id }})"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-pencil-alt"></i></button>

                                                        @if($item->is_active)
                                                            <button wire:click="confirmStatus({{ $item->id }})"
                                                                class="btn btn-danger btn-sm"><i class="fas fa-power-off"></i>
                                                            </button>
                                                        @else
                                                            <button wire:click="confirmStatus({{ $item->id }})"
                                                                class="btn btn-success btn-sm"><i class="fas fa-power-off"></i>
                                                            </button>
                                                        @endif
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
                                    {{ $userList->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- Modal Konfirmasi Hapus -->
    @if ($confirmingStatus)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    @if ($is_active == 1)
                    <div class="modal-header">
                        <h5 class="modal-title">Nonaktifkan User</h5>
                    </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin mennonaktifkan user ini?</p>
                        </div>
                        <div class="modal-footer">
                        <button wire:click="statusConfirmed" class="btn btn-danger">Ya, Nonaktifkan</button>
                        <button wire:click="$set('confirmingStatus', false)" class="btn btn-secondary">Batal</button>
                    </div>
                    @else
                    <div class="modal-header">
                        <h5 class="modal-title">Aktivasi User</h5>
                    </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin mengaktifkan user ini?</p>
                        </div>
                        <div class="modal-footer">
                        <button wire:click="statusConfirmed" class="btn btn-danger">Ya, Aktifkan</button>
                        <button wire:click="$set('confirmingStatus', false)" class="btn btn-secondary">Batal</button>
                    </div>
                    @endif
                    {{-- <div class="modal-footer">
                        <button wire:click="statusConfirmed" class="btn btn-danger">Ya, Nonaktifkan</button>
                        <button wire:click="$set('confirmingStatus', false)" class="btn btn-secondary">Batal</button>
                    </div> --}}
                </div>
            </div>
        </div>
    @endif


    <!-- Modal Konfirmasi Reset Password -->
    @if ($confirmingResetPassword)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Reset Password</h5>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin me-reset password user ini ke <code>12345678</code>?</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="resetPasswordConfirmed" class="btn btn-warning">Ya, Reset</button>
                        <button wire:click="$set('confirmingResetPassword', false)" class="btn btn-secondary">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>