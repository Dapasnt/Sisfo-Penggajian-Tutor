<?php

namespace App\Livewire\Admin\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | User List')]
class UserList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingStatus = false;
    public $confirmingResetPassword = false;
    public $search = '';
    public $id_user, $username, $password, $email, $id_role, $is_active, $user;
    public $selectedUserId;

    // protected $paginationTheme = 'bootstrap';

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $userList = User::search($this->search)->orderBy('is_active', 'desc')->paginate(10);
        $roles = Role::orderBy('nama')->get();
        return view('livewire.admin.user.user-list', compact('userList', 'roles'));
    }
    public function add()
    {
        $this->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|max:255|unique:users,email',
            'id_role' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'id_role.required' => 'Role wajib diisi.',
        ]);
        try {

            User::create([
                'username' => $this->username,
                'email' => $this->email,
                'password' => bcrypt('12345678'),
                'id_role' => $this->id_role,
                'is_active' => 1,
            ]);

            $this->resetForm();
            $this->dispatch('success-message', 'User baru berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $this->formEdit = true;
        $user = User::findOrFail($id);
        $this->id_user = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = $user->jns_kel;
        $this->id_role = $user->id_role;
    }

    public function update()
    {
        $this->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $this->id_user,
            'email' => 'required|string|max:255|unique:users,email,' . $this->id_user,
            'id_role' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'id_role.required' => 'Role wajib diisi.',
        ]);
        try {

            $user = User::findOrFail($this->id_user);
            $user->update([
                'username' => $this->username,
                'email' => $this->email,
                // 'password' => $this->password,
                'id_role' => $this->id_role
            ]);

            $this->resetForm();
            $this->dispatch('success-message', 'Data Tutor berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmStatus($id)
    {
        $this->selectedUserId = $id;
        $this->confirmingStatus = true;
        $user = User::find($id);
        $this->is_active = $user->is_active;
    }

    public function statusConfirmed()
    {
        try {
            $user = User::find($this->selectedUserId);

            // Logika Saklar: Kalau 1 jadi 0, Kalau 0 jadi 1
            $newStatus = $user->is_active == 1 ? 0 : 1;

            $user->update(['is_active' => $newStatus]);
            
            $statusText = $newStatus == 1 ? 'diaktifkan' : 'dinonaktifkan';
            $this->confirmingStatus = false;
            $this->dispatch('success-message', "User berhasil $statusText.");
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->id_user = '';
        $this->email = '';
        $this->username = '';
        $this->id_role = '';
        $this->resetErrorBag();
    }

    public function confirmResetPassword()
    {
        $this->confirmingResetPassword = true;
    }
    public function resetPasswordConfirmed()
    {
        // dd($this->password);
        try {
            $user = User::findOrFail($this->id_user);
            $user->password = bcrypt('12345678');
            $user->save();

            $this->confirmingResetPassword = false;
            $this->dispatch('success-message', 'Password berhasil direset.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
