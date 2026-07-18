<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = ['nama_divisi', 'fokus_utama', 'keterangan_integrasi'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_koordinator')->withTimestamps();
    }

    public function adminTasks()
    {
        return $this->hasMany(AdminTask::class);
    }

    public function scopeKoordinators($query)
    {
        return $query->whereHas('users', function ($q) {
            $q->where('is_koordinator', true);
        });
    }
}
