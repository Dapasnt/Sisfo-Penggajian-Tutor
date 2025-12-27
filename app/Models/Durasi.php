<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Durasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'durasi';
    protected $primaryKey = 'id_durasi';
    protected $guarded = [];
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('durasi', 'LIKE', '%' . $keyword . '%')
                ->orWhere('tarif', 'LIKE', '%' . $keyword . '%');
        });
    }
}
