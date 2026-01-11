<?php

namespace App\Livewire\Admin\User;

use App\Models\Tutor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $username, $email;
    public $nama, $id_tutor, $no_hp, $account_holder_name, $bank_code, $account_number;

    public $user;
    public $isTutor = false;
    public $showEditModal = false;
    public $showPasswordModal = false;

    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'password' => ['required', 'confirmed', 'min:8'],
        'password_confirmation' => ['required']
    ];

    protected $messages = [
        'password.required' => 'Password tidak boleh kosong',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.min' => 'Password minimal 8 karakter',
        'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong',
    ];

    public function mount()
    {
        $this->getData();
    }

    public function getData()
    {
        $this->user = User::with(['role', 'tutor'])
            ->find(Auth::id());

        $this->isTutor = optional($this->user->tutor)->exists ?? false;
    }

    public function openPasswordModal()
    {
        $this->resetValidation();
        $this->password = '';
        $this->password_confirmation = '';
        $this->showPasswordModal = true;
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
    }

    public function updatePassword()
    {
        $this->validate();

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->closePasswordModal();
        $this->dispatch('success-message', 'Password berhasil diperbarui.');
    }

    public function openEditModal()
    {
        $this->resetValidation();
        $this->user = Auth::user();

        $this->username = $this->user->username;
        $this->email = $this->user->email;

        if ($this->user->tutor) {
            $this->nama = $this->user->tutor->nama;
            $this->id_tutor = $this->user->tutor->id;
            $this->no_hp = $this->user->tutor->no_hp;
            $this->account_holder_name = $this->user->tutor->account_holder_name;
            $this->account_number = $this->user->tutor->account_number;
            $this->bank_code = $this->user->tutor->bank_code;
        }

        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
    }

    public function updateProfile()
    {
        $tutor = Tutor::with('user')->findOrFail($this->id_tutor);
        $userId = $tutor->id_user;
        $this->validate([
            'nama' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'mapel' => 'required',
            'jns_kel' => 'required|string',
            'no_hp' => 'required',
            'bank_code' => 'required',
            'account_number' => 'required',
            'account_holder_name' => 'required',
            // Validasi User (Ignore ID user saat ini agar bisa simpan tanpa ganti email/username)
            'username' => 'required|string|unique:users,username,' . $userId,
            'email' => 'required|email|unique:users,email,' . $userId,
        ]);

        $this->user->update([
            'username' => $this->username,
            'email' => $this->email,
        ]);

        $this->user->tutor->update([
            'nama' => $this->nama,
            'pendidikan' => $this->pendidikan,
            'mapel' => $this->mapel,
            'jns_kel' => $this->jns_kel,
            'no_hp' => $this->no_hp,
            'bank_code' => $this->bank_code,
            'account_number' => $this->account_number,
            'account_holder_name' => $this->account_holder_name,
        ]);

        $this->closeEditModal();
        $this->getData();
        $this->dispatch('success-message', 'Profil berhasil diperbarui.');
    }
    public function render()
    {
        return view('livewire.admin.user.profile');
    }
}
