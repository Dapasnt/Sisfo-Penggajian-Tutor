<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tutor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tutor';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'LIKE', '%' . $keyword . '%')
                ->orWhere('mapel', 'LIKE', '%' . $keyword . '%')
                ->orWhere('no_hp', 'LIKE', '%' . $keyword . '%')
                ->orWhere('jns_kel', 'LIKE', '%' . $keyword . '%')
                ->orWhere('bank_code', 'LIKE', '%' . $keyword . '%')
                ->orWhere('account_number', 'LIKE', '%' . $keyword . '%')
                ->orWhere('account_holder_name', 'LIKE', '%' . $keyword . '%')
                ;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class,'id_user', 'id'); 
    }
}
