<div>
    @if ($showEditModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog modal-lg" role="document">
            <form wire:submit.prevent="updateProfile" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil</h5>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label>Username</label>
                        <input type="text" wire:model.defer="username" class="form-control">
                        @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" wire:model.defer="email" class="form-control">
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Nama Tutor</label>
                        <input type="text" wire:model.defer="nama" class="form-control">
                        @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>ID Tutor</label>
                        <input type="text" wire:model.defer="id_tutor" class="form-control" readonly>
                        @error('id_tutor') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>No HP</label>
                        <input type="text" wire:model.defer="no_hp" class="form-control">
                        @error('no_hp') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nomor Rekening</label>
                        <input wire:model.defer="account_number" class="form-control"></input>
                        @error('account_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nama Bank (ex. BCA, BNI, BRI)</label>
                        <input wire:model.defer="bank_code" class="form-control"></input>
                        @error('bank_code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nama Pemegang Rekening</label>
                        <input wire:model.defer="account_holder_name" class="form-control"></input>
                        @error('account_holder_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" wire:target="updateProfile" wire:loading.remove class="btn btn-primary">Simpan Perubahan</button>
                    <button wire:target="updateProfile" wire:loading class="btn btn-primary"><i class="fas fa-spinner fa-spin"></i> Loading...</button>
                    <button type="button" wire:click="closeEditModal" class="btn btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <section class="section">
        <div class="section-header">
            <h1>Profil Saya</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card profile-widget">
                        <div class="profile-widget-header">
                            <img alt="image" src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&size=100" class="rounded-circle profile-widget-picture">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Role</div>
                                    <div class="profile-widget-item-value">{{ ucfirst($user->role->nama ?? '-') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $user->username }} <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{ $user->email }}
                                </div>
                            </div>
                            {{-- <p>Jika Anda ingin mengubah password, klik tombol di bawah.</p> --}}
                        </div>

                        <div class="card-footer text-center">
                            <button wire:click="openPasswordModal" class="btn btn-primary btn-lg btn-round">
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>

                @if ($isTutor)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Informasi tutor</h4>
                            <button wire:click="openEditModal" class="btn btn-primary btn-sm text-end"><i class="fas fa-pencil-alt"></i> Edit</button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Nama:</strong> {{ $user->tutor->nama ?? '-' }}</li>
                                <li class="list-group-item"><strong>Id Tutor:</strong> {{ $user->tutor->id ?? '-' }}</li>
                                <li class="list-group-item"><strong>Jenis Kelamin:</strong> {{ $user->tutor->jns_kel ?? '-' }}</li>
                                <li class="list-group-item"><strong>Mapel:</strong> {{ $user->tutor->mapel ?? '-' }}</li>
                                <li class="list-group-item"><strong>Pendidikan:</strong> {{ $user->tutor->pendidikan ?? '-' }}</li>
                                <li class="list-group-item"><strong>No HP:</strong> {{ $user->tutor->no_hp ?? '-' }}</li>
                                <li class="list-group-item"><strong>Nomor Rekening:</strong> {{ $user->tutor->account_number ?? '-' }}</li>
                                <li class="list-group-item"><strong>Nama Bank:</strong> {{ $user->tutor->bank_code ?? '-' }}</li>
                                <li class="list-group-item"><strong>Nama Pemegang Rekening:</strong> {{ $user->tutor->account_holder_name ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if ($showPasswordModal)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Password</h5>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updatePassword">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input wire:model.defer="password" type="password" id="password" class="form-control">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input wire:model.defer="password_confirmation" type="password" id="password_confirmation" class="form-control">
                            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" wire:click="closePasswordModal" class="btn btn-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
