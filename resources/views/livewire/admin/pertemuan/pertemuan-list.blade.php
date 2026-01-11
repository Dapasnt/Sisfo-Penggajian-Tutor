public function generate()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        try {
            DB::transaction(function () {
                $listPertemuan = Pertemuan::whereMonth('created_at', $this->bulan)
                    ->whereYear('created_at', $this->tahun)
                    ->where('status', 'Hadir')
                    ->get();

                if ($listPertemuan->isEmpty()) {
                    return "Tidak ada data pertemuan untuk periode ini.";
                }

                $grouped = $listPertemuan->groupBy('id_tutor');

                foreach ($grouped as $idTutor => $absenTutor) {
                    //Hitung Total Honor dan Jumlah Pertemuan
                    $totalHonor = $absenTutor->sum('tarif_saat_itu');
                    $jumlahPertemuan = $absenTutor->count();
                    $totalDurasi = $absenTutor->sum(function ($item) {
                        return $item->durasi->durasi ?? 0;
                    });

                    $gaji = Penggajian::updateOrCreate(
                        [
                            'id_tutor'      => $idTutor,
                            'periode_bulan' => $this->bulan,
                            'periode_tahun' => $this->tahun,
                        ],
                        [
                            'total_pertemuan' => $jumlahPertemuan,
                            'total_honor'     => $totalHonor,
                            'gaji_dibayar'    => $totalHonor,
                            'total_durasi'    => $totalDurasi,
                        ]
                    );
                    $idPertemuanTutor = $absenTutor->pluck('id');
                    Pertemuan::whereIn('id', $idPertemuanTutor)
                        ->update(['id_penggajian' => $gaji->id_penggajian]);
                }
            });
            $this->formTgl = false;
            $this->resetPage();
            $this->dispatch('success-message', 'Hitung gaji baru berhasil dilakukan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }