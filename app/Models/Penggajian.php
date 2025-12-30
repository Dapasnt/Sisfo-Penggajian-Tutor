<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penggajian extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'penggajian';
    protected $primaryKey = 'id_penggajian';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('periode_bulan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('periode_tahun', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_pertemuan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_honor', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_durasi', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_pertemuan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('gaji_dibayar', 'LIKE', '%' . $keyword . '%')
                ->orWhere('status_pembayaran', 'LIKE', '%' . $keyword . '%')
                ->orWhere('xendit_external_id', 'LIKE', '%' . $keyword . '%')
                ->orWhere('batch_id', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('tutor', function ($tq) use ($keyword) {
                    $tq->where('nama', 'like', '%' . $keyword . '%');
                });
        });
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id');
    }
    public function kelas() {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
    public function durasi() {
        return $this->belongsTo(Durasi::class, 'id_durasi', 'id_durasi');
    }
    public function jenjang() {
        return $this->belongsTo(Jenjang::class, 'id_jenjang', 'id_jenjang');
    }
    public function pertemuan() {
        // return $this->belongsTo(Pertemuan::class, 'id_pertemuan', 'id');
        return $this->hasMany(Pertemuan::class, 'id_penggajian', 'id_penggajian');
    }
}
