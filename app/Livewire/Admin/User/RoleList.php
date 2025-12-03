<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Role')]
class RoleList extends Component
{

    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $id_role, $nama, $desc;
    public $selectedRoleId;

    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roleList = Role::search($this->search)->orderBy('nama')->paginate(10);
        return view('livewire.admin.user.role-list', compact('roleList'));
    }

    // public function add()
    // {
    //     $this->validate([
    //         'nama' => 'required|string|max:255',
    //         'desc' => 'required',
    //     ], [
    //         'nama.required' => 'Nama wajib diisi.',
    //         'desc.required' => 'Deskripsi wajib diisi.',
    //     ]);
    //     try {
    
    //         Tutor::create([
    //             'nama' => $this->nama,
    //             'mapel' => $this->mapel,
    //             'jns_kel' => $this->jns_kel,
    //             'no_hp' => $this->no_hp
    //         ]);
    
    //         $this->resetForm();
    //         $this->dispatch('success-message', 'Tutor berhasil ditambahkan.');
    //     } catch (\Throwable $th) {
    //         $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
    //     }
    // }

    public function edit($id)
    {
        $this->formEdit = true;
        $role = Role::findOrFail($id);
        $this->id_role = $role->id;
        $this->nama = $role->nama;
        $this->desc = $role->mapel;
    }

    public function update()
    {
        $this->validate([
            'desc' => 'required|string|max:255|unique:roles,desc'. $this->role_id,
        ], [
            'desc.required' => 'Deskripsi wajib diisi.',
            'desc.unique' => 'Deskripsi sudah digunakan.',
        ]);
        try {
    
            $tutor = Role::findOrFail($this->id_role);
            $tutor->update([
                'desc' => $this->desc,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Roles berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // public function confirmDelete($id)
    // {
    //     $this->selectedTutorId = $id;
    //     $this->confirmingDelete = true;
    // }

    // public function deleteConfirmed()
    // {
    //     try {
    //         $tutor = Tutor::findOrFail($this->selectedTutorId);
    //         $tutor->delete();
    
    //         $this->confirmingDelete = false;
    //         $this->dispatch('success-message', 'Data Tutor berhasil dihapus.');
    //     } catch (\Throwable $th) {
    //         $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
    //     }
    // }

    public function resetForm()
    {
        // $this->formAdd = false;
        $this->formEdit = false;
        $this->id_role = '';
        $this->nama = '';
        $this->desc = '';
        $this->resetErrorBag();
    }
}
