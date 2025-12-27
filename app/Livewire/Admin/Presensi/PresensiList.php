<?php

namespace App\Livewire\Admin\Presensi;

use App\Models\Durasi;
use App\Models\Jenjang;
use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | Presensi')]
class PresensiList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $formAdd = false;
    public $formRetake = false;
    public $formDetail = false;
    public $detailData;
    public $search = '';

    public $id_tutor, $id_kelas, $keterangan, $nama_tutor_display, $photo, $tgl_pertemuan, $status, $bukti_foto, $id_pertemuan, $id_jenjang, $id_durasi;

    public $daftarKelas = [];
    public $daftarTutor = [];
    public $daftarJenjang = [];
    public $daftarDurasi = [];

    public function mount()
    {
        $this->daftarKelas = Kelas::all();
        $this->daftarJenjang = Jenjang::all();
        $this->daftarDurasi = Durasi::all();
        $user = Auth::user();

        if ($user && $user->tutor) {
            $this->id_tutor = $user->tutor->id;
            $this->nama_tutor_display = $user->tutor->nama;
        } else {
            $this->daftarTutor = Tutor::all();
        }
    }
    public function render()
    {
        $query = Pertemuan::with('tutor')
        ->orderBy('created_at', 'desc');

        $user = Auth::user();

        if ($user->tutor) {
            $query->where('id_tutor', $user->tutor->id); 
        } else {
            if ($this->search){
                $query->whereHas('tutor', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%');
            });
            }
        }
        $presensiList = $query->paginate(10);

        return view('livewire.admin.presensi.presensi-list', [
            'presensiList' => $presensiList
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->formAdd = true;
        $this->dispatch('open-camera');
    }

    public function store()
    {
        $this->validate([
            'id_kelas' => 'required',
            'photo'    => 'required',
        ]);

        $kelas = Kelas::where('id_kelas', $this->id_kelas)->first();
        $jenjang = Jenjang::where('id_jenjang', $this->id_jenjang)->first();
        $durasi = Durasi::where('id_durasi', $this->id_durasi)->first();
        $tarifFix = ($kelas->tarif ?? 0) + ($jenjang->tarif ?? 0) + ($durasi->tarif ?? 0);

        $imageParts = explode(";base64,", $this->photo);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'bukti_' . uniqid() . '.png';
        $path = 'bukti-foto/' . $fileName;
        Storage::disk('public')->put($path, $imageBase64);

        try {
            Pertemuan::create([
                'id_tutor'       => Auth::user()->tutor->id,
                'id_kelas'       => $this->id_kelas,
                'id_jenjang'     => $this->id_jenjang,
                'id_durasi'      => $this->id_durasi,
                'tgl_pertemuan'  => now(),
                'tarif_saat_itu' => $tarifFix,
                'id_penggajian'  => null,
                'bukti_foto'     => $path,
                'keterangan'     => $this->keterangan,
                'status'         => 'Pending',
            ]);

            $this->resetForm();

            $this->dispatch('success-message', 'Presensi berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function detail($id)
    {
        $this->resetForm();
        // $this->formDetail = true;
        $this->detailData = Pertemuan::with(['tutor', 'kelas', 'durasi', 'jenjang'])->find($id);

        if($this->detailData) {
            $this->formDetail = true; // Aktifkan Mode Detail
        }
        // $pertemuan = Pertemuan::findOrFail($id);
        // $this->tgl_pertemuan = $pertemuan->tgl_pertemuan;
        // $this->bukti_foto = $pertemuan->bukti_foto;
        // $this->keterangan = $pertemuan->keterangan;
        // $this->status = $pertemuan->status;
    }

    public function retake($id)
    {
        $this->formRetake = true;
        $pertemuan = Pertemuan::findOrFail($id);
        $this->id_pertemuan = $pertemuan->id;
        $this->tgl_pertemuan = $pertemuan->tgl_pertemuan;
        $this->keterangan = $pertemuan->keterangan;
        $this->status = $pertemuan->status;
        $this->id_kelas = $pertemuan->id_kelas;
        $this->id_durasi = $pertemuan->durasi->id_durasi;
        $this->id_jenjang = $pertemuan->jenjang->id_jenjang;
        // $this->resetForm();
        $this->dispatch('open-camera');
    }

    public function update()
    {
        $this->validate([
            'photo'    => 'required',
        ]);

        $imageParts = explode(";base64,", $this->photo);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'bukti_' . uniqid() . '.png';
        $path = 'bukti-foto/' . $fileName;
        Storage::disk('public')->put($path, $imageBase64);

        try {
            $pertemuan = Pertemuan::findOrFail($this->id_pertemuan);
            $pertemuan->update([
                'bukti_foto' => $path,
                'status'     => 'Pending',
                'keterangan'     => $this->keterangan,
                'id_durasi'     => $this->id_durasi,
                'id_jenjang'     => $this->id_jenjang,
                'id_kelas'     => $this->id_kelas,
            ]);

            $this->resetForm();

            $this->dispatch('success-message', 'Presensi ulang berhasil dilakukan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function update_hadir($id)
    {
        try {
            $pertemuan = Pertemuan::findOrFail($id);
            $pertemuan->update([
                'status' => 'Hadir',
            ]);
    
            $this->dispatch('success-message', 'Data pertemuan berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
    public function update_return($id)
    {
        try {
            $pertemuan = Pertemuan::findOrFail($id);
            $pertemuan->update([
                'status' => 'Return',
            ]);
    
            $this->dispatch('success-message', 'Data pertemuan berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    
    public function resetForm()
    {
        $this->dispatch('close-camera');

        $this->id_tutor = ''; 
        $this->id_kelas = '';
        $this->id_jenjang = '';
        $this->id_durasi = '';
        $this->photo = null;
        $this->keterangan = '';
        $this->formAdd = false;
        $this->formRetake = false;
        $this->formDetail = false;
    }
}
