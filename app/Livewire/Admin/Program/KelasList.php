<?php

namespace App\Livewire\Admin\Program;

use App\Models\Kelas;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Kelas')]

class KelasList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $id_kelas, $nama, $deskripsi, $tarif, $jenjang;
    public $selectedIdKelas;

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $kelasList = Kelas::search($this->search)->paginate(10);
        return view('livewire.admin.program.kelas-list', compact('kelasList'));
    }

    public function add()
    {
        $this->validate([
            'id_kelas' => 'required|string|max:255|unique:kelas,id_kelas',
            'nama' => 'required|string|max:255|unique:kelas,nama',
            'deskripsi' => 'nullable|string',
            'tarif' => 'required|integer',
        ], [
            'id_kelas.required' => 'ID kelas wajib diisi.',
            'nama.required' => 'Nama kelas wajib diisi.',
            'tarif.required' => 'Tarif kelas wajib diisi.',
        ]);
        try {
    
            Kelas::create([
                'id_kelas' => $this->id_kelas,
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Kelas berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id_kelas)
    {
        // dd($id);
        $this->formEdit = true;
        $kelas = Kelas::findOrFail($id_kelas);
        $this->id_kelas = $kelas->id_kelas;
        $this->nama = $kelas->nama;
        $this->deskripsi = $kelas->deskripsi;
        $this->tarif = $kelas->tarif;
    }

    public function update()
    {
        $this->validate([
            'id_kelas' => 'required|string|max:255|unique:kelas,id_kelas,'. $this->id_kelas . ',id_kelas',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tarif' => 'required|integer',
        ], [
            'id_kelas.required' => 'ID kelas wajib diisi.',
            'nama.required' => 'Nama kelas wajib diisi.',
            'tarif.required' => 'tarif kelas wajib diisi.',
        ]);
        try {
            $kelas = Kelas::findOrFail($this->id_kelas);
            $kelas->update([
                'id_kelas' => $this->id_kelas,
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data Kelas berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedIdKelas = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $kelas = Kelas::findOrFail($this->selectedIdKelas);
            $kelas->delete();
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data Kelas berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->id_kelas = '';
        $this->nama = '';
        $this->deskripsi = '';
        $this->tarif = '';
        $this->resetErrorBag();
    }
}
