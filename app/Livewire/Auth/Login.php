<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Login extends Component
{
    public $usermail, $password;

    #[Layout('components.layouts.auth')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        try {
            $this->validate([
                'usermail' => 'required|string',
                'password' => 'required|string',
            ]);

            $usermail_type = filter_var($this->usermail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $credentials = [
                $usermail_type => $this->usermail,
                'password' => $this->password
            ];

            if (Auth::attempt($credentials)) {
                session()->regenerate();
                return $this->redirect(route('admin.tutor.view'));
            } else {
                $this->dispatch('failed-message', 'Akun tidak ditemukan atau password salah.');
                // $this->dispatchBrowserEvent('failed-message', ['message' => 'Terjadi kesalahan: ' . $th->getMessage()]);

            }
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
