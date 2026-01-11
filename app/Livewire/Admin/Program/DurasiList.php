<?php

namespace App\Livewire\Admin\Program;

use App\Models\Durasi;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Durasi')]
class DurasiList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $id_durasi, $durasi, $tarif;
    public $selectedIdDurasi;
    public function render()
    {
        $durasiList = Durasi::search($this->search)->paginate(10);
        return view('livewire.admin.program.durasi-list', compact('durasiList'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function add()
    {
        $this->validate([
            'durasi' => 'required|integer',
            'tarif' => 'required|integer',
        ], [
            'durasi.required' => 'Durasi mengajar wajib diisi.',
            'tarif.required' => 'Tarif durasi wajib diisi.',
        ]);
        try {
    
            Durasi::create([
                'durasi' => $this->durasi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Tarif durasi baru berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id_durasi)
    {
        // dd($id);
        $this->formEdit = true;
        $durasi = Durasi::findOrFail($id_durasi);
        $this->id_durasi = $durasi->id_durasi;
        $this->durasi = $durasi->durasi;
        $this->tarif = $durasi->tarif;
    }

    public function update()
    {
        $this->validate([
            'durasi' => 'required|integer',
            'tarif' => 'required|integer',
        ], [
            'durasi.required' => 'Durasi mengajar wajib diisi.',
            'tarif.required' => 'Tarif durasi wajib diisi.',
        ]);
        try {
            $durasi = Durasi::findOrFail($this->id_durasi);
            $durasi->update([
                'durasi' => $this->durasi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data durasi berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedIdDurasi = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $durasi = durasi::findOrFail($this->selectedIddurasi);
            $durasi->delete();
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Tarif durasi berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->id_durasi = '';
        $this->tarif = '';
        $this->resetErrorBag();
    }
}
