<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'roles';
    protected $guarded = [];

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', '%' . $keyword . '%')
                ->orWhere('desc', 'like', '%' . $keyword . '%');
        });
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
