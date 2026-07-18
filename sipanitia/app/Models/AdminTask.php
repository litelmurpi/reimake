<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;

class AdminTask extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'divisi_id', 'nama_tugas', 'target_tanggal', 'pj_user_id',
        'status', 'catatan', 'last_modified_by', 'last_modified_at'
    ];

    protected $casts = [
        'target_tanggal' => 'date',
        'last_modified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'catatan', 'nama_tugas', 'target_tanggal', 'pj_user_id', 'divisi_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function pjUser()
    {
        return $this->belongsTo(User::class, 'pj_user_id');
    }

    public function lastModifiedByUser()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function scopeOverdue(Builder $query)
    {
        return $query->where('target_tanggal', '<', today())->where('status', '!=', 'Selesai');
    }

    public function scopeByStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }
}
