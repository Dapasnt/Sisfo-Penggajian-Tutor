<div>

@if ($formEdit)
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Edit Role</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h4>Form Role</h4></div>
                    <div class="card-body">
                        <form wire:submit.prevent="update" autocomplete="off">
                            <!-- Nama Role -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Nama Role</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" class="form-control" wire:model.defer="nama" readonly>
                                    @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea class="form-control" wire:model.defer="desc" rows="5"></textarea>
                                    @error('desc') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            <!-- Tombol -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7">
                                    <button wire:loading.remove wire:target="update" type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                    <button wire:loading wire:target="update" class="btn btn-primary">
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
        <h1>Data Role</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex flex-column align-items-start">
                        <div class="w-100 d-flex justify-content-between align-items-center mb-2">
                            <h4 class="mb-0">List Role</h4>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="float-right">
                            <div class="d-flex jutstify-content-end column-gap-1">
                                <div class="input-group">
                                   <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="cari ...">
                                   <div class="input-group-append">
                                       <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                   </div>
                               </div>
                               {{-- <button wire:loading.remove wire:target="refresh" wire:click="refresh" class="btn btn-primary"><i class="fas fa-sync"></i></button>
                               <button wire:loading wire:target="refresh" class="btn btn-primary"><i class="fas fa-sync fa-spin"></i></button> --}}
                            </div>
                        </div>
    
                        <div class="clearfix mb-3"></div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roleList as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration + ($roleList->firstItem() - 1) }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->desc }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button wire:click="edit({{ $item->id }})" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i></button>
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
                            {{ $roleList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

</div>

