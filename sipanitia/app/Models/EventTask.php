<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;

class EventTask extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'event_id', 'sub_event', 'kebutuhan_aspek', 'target_tanggal',
        'pj_user_id', 'mc_pengisi', 'perkap_utama', 'detail_deskripsi',
        'status', 'catatan_plan_b', 'last_modified_by', 'last_modified_at'
    ];

    protected $casts = [
        'target_tanggal' => 'date',
        'last_modified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'catatan_plan_b', 'kebutuhan_aspek', 'target_tanggal', 'pj_user_id', 'mc_pengisi', 'perkap_utama'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
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

    public function scopeBySubEvent(Builder $query, $subEvent)
    {
        return $query->where('sub_event', $subEvent);
    }
}
