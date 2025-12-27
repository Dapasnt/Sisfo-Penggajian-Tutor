<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('GTC | Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard.dashboard');
    }
}
