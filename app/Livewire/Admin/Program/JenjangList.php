<?php

namespace App\Livewire\Admin\Program;

use App\Models\Jenjang;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('GTC | List Jenjang')]

class JenjangList extends Component
{
    use WithPagination;

    public $formAdd = false, $formEdit = false, $confirmingDelete = false;
    public $search = '';
    public $id_jenjang, $nama, $deskripsi, $tarif;
    public $selectedIdJenjang;

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $jenjangList = Jenjang::search($this->search)->paginate(10);
        return view('livewire.admin.program.jenjang-list', compact('jenjangList'));
    }

    public function add()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:jenjang,nama',
            'deskripsi' => 'nullable|string',
            'tarif' => 'required|integer',
        ], [
            'nama.required' => 'Nama jenjang wajib diisi.',
            'tarif.required' => 'Tarif jenjang wajib diisi.',
        ]);
        try {
    
            Jenjang::create([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Jenjang berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function edit($id_jenjang)
    {
        // dd($id);
        $this->formEdit = true;
        $jenjang = Jenjang::findOrFail($id_jenjang);
        $this->id_jenjang = $jenjang->id;
        $this->nama = $jenjang->nama;
        $this->deskripsi = $jenjang->deskripsi;
        $this->tarif = $jenjang->tarif;
    }

    public function update()
    {
        $this->validate([
            'nama' => 'required|string|max:255|unique:jenjang,nama,'. $this->nama . ',nama',
            'deskripsi' => 'nullable|string',
            'tarif' => 'required|integer',
        ], [
            'nama.required' => 'Nama jenjang wajib diisi.',
            'tarif.required' => 'Tarif jenjang wajib diisi.',
        ]);
        try {
            $jenjang = Jenjang::findOrFail($this->id_jenjang);
            $jenjang->update([
                'nama' => $this->nama,
                'deskripsi' => $this->deskripsi,
                'tarif' => $this->tarif,
            ]);
    
            $this->resetForm();
            $this->dispatch('success-message', 'Data jenjang berhasil diubah.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedIdJenjang = $id;
        $this->confirmingDelete = true;
    }

    public function deleteConfirmed()
    {
        try {
            $jenjang = jenjang::findOrFail($this->selectedIdJenjang);
            $jenjang->delete();
    
            $this->confirmingDelete = false;
            $this->dispatch('success-message', 'Data jenjang berhasil dihapus.');
        } catch (\Throwable $th) {
            $this->dispatch('failed-message', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function resetForm()
    {
        $this->formAdd = false;
        $this->formEdit = false;
        $this->id_jenjang = '';
        $this->nama = '';
        $this->deskripsi = '';
        $this->tarif = '';
        $this->resetErrorBag();
    }
}
