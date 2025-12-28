<div>
    @script
    <script>
        let stream = null;

        Livewire.on('open-camera', () => {
            // Beri jeda agar elemen HTML sempat dirender
            setTimeout(() => {
                const video = document.getElementById("videoElement");
                const btnAction = document.getElementById("btnCameraAction"); // Tombol tunggal kita

                if (!video || !btnAction) {
                    console.error("Elemen kamera tidak lengkap!");
                    return;
                }

                // 1. Nyalakan Stream Kamera
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(function (s) {
                            stream = s;
                            video.srcObject = stream;
                            video.play();
                            video.style.display = 'block'; // Pastikan video terlihat di awal
                        })
                        .catch(function (err) {
                            alert("Gagal akses kamera: " + err.message);
                        });
                }

                // 2. Pasang Event Listener pada Tombol Tunggal
                btnAction.onclick = function () {
                    const isCapturing = video.style.display !== 'none';

                    if (isCapturing) {
                        // --- MODE AMBIL FOTO ---
                        console.log("Cekrek! Mengambil foto...");

                        let canvas = document.createElement('canvas');

                        // 1. ATUR UKURAN MAKSIMAL (RESIZE)
                        // Agar tidak terlalu besar, kita batasi misalnya lebar max 800px
                        // Ini akan sangat mengurangi ukuran file tanpa merusak kualitas tampilan di HP
                        const MAX_WIDTH = 800;
                        const MAX_HEIGHT = 800;
                        let width = video.videoWidth;
                        let height = video.videoHeight;

                        // Hitung aspek rasio baru
                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                        }

                        // Set ukuran canvas sesuai hasil resize
                        canvas.width = width;
                        canvas.height = height;

                        let context = canvas.getContext('2d');
                        // Gambar video ke canvas dengan ukuran baru
                        context.drawImage(video, 0, 0, width, height);

                        // 2. GANTI FORMAT KE JPEG & TURUNKAN KUALITAS
                        // image/jpeg lebih ringan untuk foto dibanding png
                        // 0.7 artinya kualitas 70% (sudah cukup jelas untuk presensi)
                        let dataUrl = canvas.toDataURL('image/jpeg', 0.7);

                        // Kirim ke Livewire
                        @this.set('photo', dataUrl);

                        // Sembunyikan video
                        video.style.display = 'none';

                    } else {
                        // ... kode mode retake sama seperti sebelumnya ...
                        console.log("Mengulangi foto...");
                        @this.set('photo', null);
                        video.style.display = 'block';
                    }
                };

            }, 300);
        });

        Livewire.on('close-camera', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        });
    </script>
    @endscript
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($formAdd || $formRetake)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ $formAdd ? 'Tambah Presensi' : 'Retake Presensi' }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Ambil Foto Selfie</h4>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="{{ $formAdd ? 'store' : 'update' }}">

                                    <div class="form-group {{ $formAdd ? 'd-none' : '' }}">
                                        <label>Tanggal Pertemuan</label>
                                        <input type="text" class="form-control" wire:model.defer="tgl_pertemuan" readonly>
                                        @error('tgl_pertemuan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    @if(!Auth::check() || Auth::user()->role != 'tutor')
                                        <div class="form-group">
                                            <label>ID Tutor</label>
                                            <input type="text" class="form-control" wire:model.defer="id"
                                                value="{{ Auth::user()->tutor->id }}" readonly>
                                            @error('id') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Tutor</label>
                                            <input type="text" class="form-control" wire:model.defer="nama_tutor"
                                                value="{{ Auth::user()->tutor->nama }}" readonly>
                                            @error('nama_tutor') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label>Kelas</label>
                                        <select class="form-control" wire:model.defer="id_kelas">
                                            <option value="">-- Pilih Kelas yang Diajar --</option>
                                            @foreach($daftarKelas as $k)
                                                <option value="{{ $k->id_kelas }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_kelas') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Jenjang</label>
                                        <select class="form-control" wire:model.defer="id_jenjang">
                                            <option value="">-- Pilih Jenjang --</option>
                                            @foreach($daftarJenjang as $k)
                                                <option value="{{ $k->id_jenjang }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_jenjang') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Durasi</label>
                                        <select class="form-control" wire:model.defer="id_durasi">
                                            <option value="">-- Pilih Durasi Mengajar --</option>
                                            @foreach($daftarDurasi as $k)
                                                <option value="{{ $k->id_durasi }}">{{ $k->durasi }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_durasi') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group text-center">
                                        <label>Bukti Foto</label>
                                        <div wire:ignore>
                                            <video id="videoElement" autoplay playsinline
                                                style="width: 100%; max-height: 400px; border-radius: 8px;"></video>
                                        </div>

                                        @if($photo)
                                            <div class="mt-3">
                                                <p class="text-success font-weight-bold"><i class="fas fa-check-circle"></i>
                                                    Foto berhasil diambil</p>
                                                <img src="{{ $photo }}" class="img-fluid rounded"
                                                    style="max-height: 300px; border: 2px solid #6777ef;">
                                            </div>
                                        @endif

                                        @error('photo') <div class="text-danger mt-2">Wajib mengambil foto selfie!</div>
                                        @enderror

                                        <div class="mt-3">
                                            <button type="button" class="btn btn-warning" id="btnCameraAction">
                                                @if($photo)
                                                    <i class="fas fa-sync mr-1"></i> Ulangi Foto
                                                @else
                                                    <i class="fas fa-camera mr-1"></i> Ambil Foto
                                                @endif
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Keterangan (Opsional)</label>
                                        <textarea class="form-control" wire:model.defer="keterangan"
                                            style="height: 80px"></textarea>
                                    </div>

                                    <div class="form-group text-right">
                                        <button wire:loading.remove wire:target="store,update" type="submit"
                                            class="btn btn-primary btn-lg">
                                            Kirim Presensi
                                        </button>
                                        <button wire:loading wire:target="store,update"
                                            class="btn btn-primary btn-lg disabled">
                                            Mengirim...
                                        </button>
                                        <button wire:click="resetForm" type="button"
                                            class="btn btn-secondary btn-lg">Kembali
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($formDetail && $detailData)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Detail Presensi</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Pertemuan</h4>
                                <div class="card-header-action">
                                    <span class="badge badge-{{ $detailData->status == 'Hadir' ? 'success' : 'warning' }}">
                                        {{ $detailData->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Waktu Presensi</label>
                                    <h6>{{ \Carbon\Carbon::parse($detailData->created_at)->translatedFormat('l, d F Y - H:i') }}
                                    </h6>
                                </div>

                                <div class="form-group">
                                    <label>Nama Tutor</label>
                                    <h6>{{ $detailData->tutor->nama ?? 'Tutor Terhapus' }}</h6>
                                </div>

                                <div class="form-group">
                                    <label>Kelas</label>
                                    <h6>{{ $detailData->kelas->nama ?? $detailData->id_kelas }}</h6>
                                </div>

                                <div class="form-group">
                                    <label>Durasi Mengajar</label>
                                    <h6>{{ $detailData->durasi->durasi ?? $detailData->durasi->id }}</h6>
                                </div>

                                <div class="form-group">
                                    <label>Jenjang</label>
                                    <h6>{{ $detailData->jenjang->nama ?? $detailData->jenjang->id }}</h6>
                                </div>

                                <div class="form-group text-center">
                                    <label class="d-block">Bukti Foto</label>
                                    <div class="p-2 border rounded bg-light d-inline-block">
                                        @if($detailData->bukti_foto)
                                            <img src="{{ asset('storage/' . $detailData->bukti_foto) }}"
                                                class="img-fluid rounded" style="max-height: 400px; cursor: pointer"
                                                onclick="window.open(this.src)">
                                        @else
                                            <span class="text-muted">Tidak ada foto</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" style="height: 100px"
                                        readonly>{{ $detailData->keterangan ?? '-' }}</textarea>
                                </div>

                                <div class="text-right">
                                    <button wire:click="resetForm" class="btn btn-secondary">Kembali</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (!$formAdd && !$formRetake && !$formDetail)
        <section class="section">
            <div class="section-header">
                <h1>Riwayat Presensi</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>List Kehadiran</h4>
                                @if(!Auth::check() || Auth::user()->id_role != 8)
                                    <div class="d-none"></div>
                                @else
                                    <button wire:click="create" class="btn btn-primary rounded-lg">
                                        <i class="fas fa-plus"></i> Presensi Baru
                                    </button>
                                @endif
                            </div>

                            <div class="card-body">
                                <div class="float-right mb-3">
                                    <div class="input-group">
                                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control"
                                            placeholder="Cari Nama...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center">#</th>
                                                @if (!Auth::user()->tutor)
                                                    <th>Tutor</th>
                                                @endif
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
                                            @forelse ($presensiList as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration + ($presensiList->firstItem() - 1) }}
                                                    </td>
                                                    @if (!Auth::user()->tutor)
                                                        <td>{{ $item->tutor->nama }}</td>
                                                    @endif
                                                    <td class="text-center">
                                                        {{-- <img src="{{ asset('storage/' . $item->bukti_foto) }}" alt="Foto"
                                                            width="60" class="box-shadow-light rounded mt-2"
                                                            style="height: 60px; object-fit: cover; cursor: pointer"
                                                            onclick="window.open(this.src)"> --}}
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
                                                        @if (!Auth::user()->tutor)
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
                                                        @else
                                                            @if ($item->status == 'Return')
                                                                <button wire:click="retake('{{ $item->id }}')"
                                                                    class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-sync mr-1"></i>Retake
                                                                </button>
                                                            @else
                                                                <button wire:click="detail('{{ $item->id }}')"
                                                                    class="btn btn-sm btn-warning">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            @endif
                                                        @endif
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
                                    {{ $presensiList->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>