<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kelas';
    protected $guarded = [];
    protected $primaryKey = 'id_kelas';
    protected $keyType = 'string';
    public $incrementing = false;
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('id_kelas', 'LIKE', '%' . $keyword . '%')
                ->orWhere('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhere('deskripsi', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tarif', 'LIKE', '%' . $keyword . '%');
        });
    }
}
