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
            $q->where('bulan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tahun', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_pertemuan', 'LIKE', '%' . $keyword . '%')
                ->orWhere('total_honor', 'LIKE', '%' . $keyword . '%')
                ->orWhere('gaji_akhir', 'LIKE', '%' . $keyword . '%')
                ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('tutor', function ($tq) use ($keyword) {
                    $tq->where('nama', 'like', '%' . $keyword . '%')
                        ->orWhere('no_hp', 'like', '%' . $keyword . '%');
                });
        });
    }

    public function tutor() {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id');
    }
}
