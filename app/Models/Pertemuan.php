<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertemuan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pertemuan';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('tgl_pertemuan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tarif', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('tutor', function ($tq) use ($keyword) {
                    $tq->where('nama', 'like', '%' . $keyword . '%')
                        ->orWhere('no_hp', 'like', '%' . $keyword . '%');
                })
                ->orWhereHas('kelas', function ($kq) use ($keyword) {
                    $kq->where('nama', 'like', '%' . $keyword . '%');
                });
                // ->orWhereHas('penggajian', function ($uq) use ($keyword) {
                //     $uq->where('tgl_', 'like', '%' . $keyword . '%')
                //         ->orWhere('email', 'like', '%' . $keyword . '%');
                // })
        });
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id');
    }

    public function kelas() {
        return $this->hasMany(Kelas::class);
    }

    public function penggajian() {
        return $this->hasMany(Penggajian::class);
    }
}
