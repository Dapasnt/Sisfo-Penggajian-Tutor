<div>
    @if(!$showDetail && !$showPreviewModal)
    <section class="section">
        <div class="section-header">
            <h1>Riwayat Penggajian Saya</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Semua Riwayat Penghasilan</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th class="text-center">Jml Pertemuan</th>
                                            <th class="text-center">Total Durasi</th>
                                            <th>Total Gaji</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($riwayat as $item)
                                        <tr>
                                            <td>
                                                <div class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                                    {{ \Carbon\Carbon::createFromDate($item->periode_tahun, $item->periode_bulan, 1)->translatedFormat('F Y') }}
                                                </div>
                                                <div class="text-small text-muted">
                                                    ID: #{{ $item->xendit_external_id ?? $item->id_penggajian }}
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-light">{{ $item->total_pertemuan }} x</span>
                                            </td>

                                            <td class="text-center">
                                                {{ $item->total_durasi }} Menit
                                            </td>

                                            <td class="font-weight-bold text-success">
                                                Rp {{ number_format($item->total_honor, 0, ',', '.') }}
                                            </td>

                                            <td class="text-center">
                                                @if($item->status_pembayaran == 'LUNAS' || $item->status_pembayaran == 'Berhasil')
                                                    <div class="badge badge-success">SUDAH DIBAYAR</div>
                                                @elseif($item->status_pembayaran == 'Proses')
                                                    <div class="badge badge-warning">DIPROSES</div>
                                                @else
                                                    <div class="badge badge-secondary">PENDING</div>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <button wire:click="formDetail({{ $item->id_penggajian }})" class="btn btn-sm btn-info shadow-sm">
                                                    <i class="fas fa-list-ul"></i> Detail
                                                </button>
                                                
                                                <button wire:click="previewSlip({{ $item->id_penggajian }})" class="btn btn-sm btn-primary shadow-sm">
                                                    <i class="fas fa-file-invoice-dollar"></i> Slip
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon">
                                                        <i class="fas fa-wallet"></i>
                                                    </div>
                                                    <h2>Belum ada riwayat penggajian</h2>
                                                    <p class="lead">Seluruh riwayat gaji Anda akan muncul di sini.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{ $riwayat->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($showDetail)
       <section class="section">
           <div class="section-header">
                <div class="section-header-back">
                    <a wire:click="closeDetail" class="btn btn-icon cursor-pointer"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>Rincian Pertemuan</h1>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                         {{-- Tabel rincian pertemuan --}}
                         <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kelas</th>
                                    <th>Durasi</th>
                                    <th>Jenjang</th>
                                    <th>Honor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($detailData->pertemuan as $d)
                                <tr>
                                    <td>{{ $d->tgl_pertemuan }}</td>
                                    <td>{{ $d->kelas->nama }}</td>
                                    <td>{{ $d->durasi->durasi }} Menit</td>
                                    <td>{{ $d->jenjang->nama }}</td>
                                    <td>Rp {{ number_format($d->tarif_saat_itu) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">TOTAL HONOR</td>
                                    <td class="font-weight-bold text-success" style="font-size: 1.1em">Rp {{ number_format($detailData->total_honor, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                         </table>
                         <button wire:click="closeDetail" class="btn btn-secondary mt-3">Kembali</button>
                    </div>
                </div>
            </div>
       </section>
    @endif

    @if($showPreviewModal && $selectedGaji)
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Slip Gaji Elektronik</h5>
                    <button type="button" class="close" wire:click="closePreview" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-white">
                    <div id="print-area" class="p-4 border rounded">
                        <div class="text-center mb-4">
                            <h4 class="font-weight-bold mb-0">GALLANT TUTORING CENTER</h4>
                            <p class="text-muted small mb-0">Laporan Penghasilan Tutor</p>
                            <hr>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr><td width="100">Nama</td><td class="font-weight-bold">: {{ $selectedGaji->tutor->nama }}</td></tr>
                                    <tr><td>ID Tutor</td><td>: {{ $selectedGaji->tutor->id }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr><td width="100">Periode</td><td class="font-weight-bold">: {{ \Carbon\Carbon::createFromDate($selectedGaji->periode_tahun, $selectedGaji->periode_bulan, 1)->translatedFormat('F Y') }}</td></tr>
                                    <tr><td>Status</td><td>: <span class="badge badge-success">LUNAS</span></td></tr>
                                </table>
                            </div>
                        </div>

                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th>Kategori Kelas</th>
                                    <th>Jenjang</th>
                                    <th>Durasi</th>
                                    <th class="text-right">Tarif</th>
                                    <th class="text-center">Jml</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rincianGaji as $row)
                                <tr>
                                    <td>{{ $row['jenis_kelas'] }}</td>
                                    <td>{{ $row['jenjang'] }}</td>
                                    <td>{{ $row['durasi'] }}</td>
                                    <td class="text-right">Rp {{ number_format($row['tarif'], 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $row['jumlah_pertemuan'] }}</td>
                                    <td class="text-right">Rp {{ number_format($row['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">TOTAL DITERIMA</td>
                                    <td class="text-right font-weight-bold text-success" style="font-size: 1.1em">Rp {{ number_format($selectedGaji->total_honor, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="text-center mt-5">
                            <p class="small text-muted">Dokumen ini sah dan dicetak secara komputerisasi.</p>
                            <p class="small text-muted">{{ now()->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" wire:click="closePreview">Tutup</button>
                    {{-- <a href="{{ route('tutor.gaji.cetak', $selectedGaji->id_penggajian) }}" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Download PDF</a> --}}
                    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>