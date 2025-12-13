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
                    // LOGIKA PENTING:
                    // Cek apakah video sedang terlihat atau disembunyikan?
                    const isCapturing = video.style.display !== 'none';

                    if (isCapturing) {
                        // --- MODE AMBIL FOTO ---
                        console.log("Cekrek! Mengambil foto...");

                        let canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        let context = canvas.getContext('2d');
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        let dataUrl = canvas.toDataURL('image/png');

                        // Kirim ke Livewire
                        @this.set('photo', dataUrl);

                        // Sembunyikan video segera agar terlihat seperti "freeze"
                        video.style.display = 'none';



                    } else {
                        // --- MODE RETAKE (ULANGI) ---
                        console.log("Mengulangi foto...");

                        // Kosongkan foto di Livewire
                        @this.set('photo', null);

                        // Munculkan kembali video live stream
                        video.style.display = 'block';

                        // CATATAN: Teks tombol akan kembali jadi "Ambil Foto" otomatis oleh Livewire.
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

    @if ($formAdd)
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="resetForm" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Input Presensi</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Ambil Foto Selfie</h4>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent="store">

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

                                    <div class="form-group text-center">
                                    </div>

                                    <div class="form-group text-center">
                                        <label>Bukti Foto</label>

                                        <div wire:ignore>
                                            <video id="videoElement" autoplay playsinline
                                                style="width: 100%; max-height: 400px; background: #000; border-radius: 8px;"></video>
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
                                        <button wire:loading.remove wire:target="store" type="submit"
                                            class="btn btn-primary btn-lg">
                                            Kirim Presensi
                                        </button>
                                        <button wire:loading wire:target="store" class="btn btn-primary btn-lg disabled">
                                            Mengirim...
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

    @if (!$formAdd)
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
                                <button wire:click="create" class="btn btn-primary rounded-lg">
                                    <i class="fas fa-plus"></i> Presensi Baru
                                </button>
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
                                                        <img src="{{ asset('storage/' . $item->bukti_foto) }}" alt="Foto"
                                                            width="60" class="box-shadow-light rounded mt-2"
                                                            style="height: 60px; object-fit: cover; cursor: pointer"
                                                            onclick="window.open(this.src)">
                                                    </td>
                                                    <td>{{ $item->id_kelas }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
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
                                                            <button class="btn btn-sm btn-warning"><i class="fas fa-eye"></i></button>
                                                            <button wire:click="update_hadir('{{ $item->id }}')" class="btn btn-sm btn-success"><i class="fas fa-check"></i></button>
                                                            <button wire:click="update_return('{{ $item->id }}')" class="btn btn-sm btn-danger"><i class="fas fa-xmark"></i></button>
                                                            </div>
                                                            @else
                                                            <button class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-eye"></i></button>
                                                            @endif        
                                                        @else
                                                            <button class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-eye"></i></button>
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