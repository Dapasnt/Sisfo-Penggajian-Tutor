<?php

namespace App\Livewire\Admin\Tutor;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Tutor')]
class TutorView extends Component
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
        return view('livewire.admin.tutor.tutor-view', compact('tutorList'));
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
        $tutor = Tutor::findOrFail($id);
        $this->id_tutor = $tutor->id;
        $this->nama = $tutor->nama;
        $this->mapel = $tutor->mapel;
        $this->jns_kel = $tutor->jns_kel;
        $this->no_hp = $tutor->no_hp;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'mapel' => 'required',
            'jns_kel' => 'required|string',
            'no_hp' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'mapel.required' => 'Mata pelajaran wajib diisi.',
            'jns_kel.required' => 'Jenis kelamin wajib diisi.',
            'no_hp.required' => 'Nomor hp wajib diisi.',
        ]);
        try {
    
            $tutor = Tutor::findOrFail($this->id_tutor);
            $tutor->update([
                'nama' => $this->nama,
                'mapel' => $this->mapel,
                'jns_kel' => $this->jns_kel,
                'no_hp' => $this->no_hp
            ]);
    
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
