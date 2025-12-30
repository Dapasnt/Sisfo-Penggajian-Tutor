<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('GTC | Dashboard')]
class TutorDashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard.tutor-dashboard');
    }
}
