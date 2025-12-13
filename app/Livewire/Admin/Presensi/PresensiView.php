<?php

namespace App\Livewire\Admin\Presensi;

use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class PresensiView extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $formAdd = false;
    public $search = '';

    public $id_tutor, $id_kelas, $keterangan, $nama_tutor_display, $photo;

    public $daftarKelas = [];
    public $daftarTutor = [];

    public function mount()
    {
        $this->daftarKelas = Kelas::all();
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

        return view('livewire.admin.presensi.presensi-view', [
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
        $tarifFix = $kelas->tarif ?? 0;

        $imageParts = explode(";base64,", $this->photo);
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'bukti_' . uniqid() . '.png';
        $path = 'bukti-foto/' . $fileName;
        Storage::disk('public')->put($path, $imageBase64);

        try {
            Pertemuan::create([
                'id_tutor'       => Auth::user()->tutor->id,
                'id_kelas'       => $this->id_kelas,
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
        $this->photo = null;
        $this->keterangan = '';
        $this->formAdd = false;
    }
}
