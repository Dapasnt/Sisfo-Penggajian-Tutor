<?php

namespace App\Livewire\Admin\Tutor;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Tutor')]
class TutorList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $id_tutor, $nama, $mapel, $jns_kel, $no_hp, $email, $username;
    public $selectedTutorId;

    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tutorList = Tutor::search($this->search)->orderBy('nama')->paginate(10);
        return view('livewire.admin.tutor.tutor-list', compact('tutorList'));
    }

    public function add()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'mapel' => 'required',
            'jns_kel' => 'required|string',
            'no_hp' => 'required',
            'username' => 'required|unique:users,username',
            'email'    => 'required|email|unique:users,email',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'mapel.required' => 'Mata pelajaran wajib diisi.',
            'jns_kel.required' => 'Jenis kelamin wajib diisi.',
            'no_hp.required' => 'Nomor hp wajib diisi.',
            'email'    => 'Email wajib diisi dan harus format email yang valid.',
            'username' => 'Username wajib diisi dan harus unik.',
        ]);
        try {
            DB::transaction(function () {
                $newUser = User::create([
                    'username' => $this->username,
                    'email'    => $this->email,
                    'password' => bcrypt('12345678'),
                    'id_role'     => 8, // Role Tutor
                ]);
                Tutor::create([
                    'id_user' => $newUser->id,
                    'nama' => $this->nama,
                    'mapel' => $this->mapel,
                    'jns_kel' => $this->jns_kel,
                    'no_hp' => $this->no_hp
                ]);
            });
    
            $this->resetForm();
            $this->dispatch('success-message', 'Tutor berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $tutor = Tutor::with('user')->findOrFail($id);
        $this->id_tutor = $tutor->id;
        $this->nama = $tutor->nama;
        $this->mapel = $tutor->mapel;
        $this->jns_kel = $tutor->jns_kel;
        $this->no_hp = $tutor->no_hp;
        $this->email = $tutor->user->email;
        $this->username = $tutor->user->username;
    }

    public function update()
    {
        $tutor = Tutor::with('user')->findOrFail($this->id_tutor);
        $userId = $tutor->id_user;
        $this->validate([
            'nama' => 'required|string|max:255',
            'mapel' => 'required',
            'jns_kel' => 'required|string',
            'no_hp' => 'required',
            // Validasi User (Ignore ID user saat ini agar bisa simpan tanpa ganti email/username)
            'username' => 'required|string|unique:users,username,' . $userId,
            'email' => 'required|email|unique:users,email,' . $userId,
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'mapel.required' => 'Mata pelajaran wajib diisi.',
            'jns_kel.required' => 'Jenis kelamin wajib diisi.',
            'no_hp.required' => 'Nomor hp wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
        ]);
        try {
        // 3. Gunakan Transaction untuk update 2 tabel
        DB::transaction(function () use ($tutor) {
            
            // Update Tabel Tutor
            $tutor->update([
                'nama' => $this->nama,
                'mapel' => $this->mapel,
                'jns_kel' => $this->jns_kel,
                'no_hp' => $this->no_hp
            ]);

            // Update Tabel User (melalui relasi)
            if ($tutor->user) {
                $tutor->user->update([
                    'username' => $this->username,
                    'email' => $this->email,
                ]);
            }
        });
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data Tutor berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedTutorId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $tutor = Tutor::findOrFail($this->selectedTutorId);
            $tutor->delete();
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data Tutor berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->id_tutor = '';
        $this->nama = '';
        $this->mapel = '';
        $this->jns_kel = '';
        $this->no_hp = '';
        $this->resetErrorBag();
    }
}
