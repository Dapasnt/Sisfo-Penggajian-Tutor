<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jenjang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenjang';
    protected $primaryKey = 'id_jenjang';
    protected $guarded = [];
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tarif', 'LIKE', '%' . $keyword . '%')
                ->orWhere('deskripsi', 'LIKE', '%' . $keyword . '%');
        });
    }
}
