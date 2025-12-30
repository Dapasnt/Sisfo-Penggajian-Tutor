<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Pertemuan;
use App\Models\Tutor;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('GTC | Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $tutor = Tutor::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();
        // $presensi = Pertemuan::where('status', '!=', 'Hadir');
        $presensi = Pertemuan::where('status', '!=', 'Hadir')
                ->orderBy('created_at', 'desc')
                ->paginate(10);


        $presensi_hari_ini = Pertemuan::where('status', '=', 'Hadir')
            ->whereDate('tgl_pertemuan', '=', now())->count();
        return view('livewire.admin.dashboard.dashboard', compact('tutor', 'presensi', 'presensi_hari_ini'));
    }
}
