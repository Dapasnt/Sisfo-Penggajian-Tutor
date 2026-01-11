<div>

@if ($formAdd || $formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{ $formAdd ? 'Tambah durasi' : 'Edit durasi' }}</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Durasi</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $formAdd ? 'add' : 'update' }}" autocomplete="off">

                            <!-- Durasi -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Durasi Mengajar</label>
                                <div class="col-sm-12 col-md-2">
                                    <input type="text" class="form-control" wire:model.defer="durasi">
                                    @error('durasi') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Tarif -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tarif</label>
                                <div class="col-sm-12 col-md-4">
                                    <input class="form-control" wire:model.defer="tarif" rows="5"></input>
                                    @error('tarif') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="add,update" type="submit" class="btn btn-primary">
                                        {{ $formAdd ? 'Tambah' : 'Update' }}
                                    </button>
                                    <button wire:loading wire:target="add,update" class="btn btn-primary">
                                        Loading ...
                                    </button>
                                    <button wire:click="resetForm" type="button" class="btn btn-secondary">Kembali</button>
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
        <h1>Data durasi</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Durasi</h4>
                            <button wire:click="$set('formAdd', true)" class="btn btn-primary rounded-lg"><i class="fa-solid fa-plus mr-2"></i>Tambah durasi</button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="float-right">
                            <div class="d-flex jutstify-content-end">
                                <div class="input-group">
                                   <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="Cari ...">
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
                                        <th>Durasi</th>
                                        <th>Tarif</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($durasiList as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($durasiList->firstItem() - 1) }}</td>
                                        <td>{{ $item->durasi }} Menit</td>
                                        <td>{{ $item->tarif }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="edit('{{ $item->id_durasi }}')" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
                                                {{-- <button wire:click="edit({{ $item->id_durasi }})" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button> --}}
                                                <button wire:click="confirmDelete('{{ $item->id_durasi }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
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
                            {{ $durasiList->links() }}
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
                <p>Apakah Anda yakin ingin menghapus data durasi ini?</p>
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

