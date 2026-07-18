<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;

class ConsumptionItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'event_id', 'jenis_konsumsi', 'porsi_jumlah', 'harga_satuan',
        'total_biaya', 'pj_konsumsi_user_id', 'kru_pembantu', 'perlengkapan',
        'status_kesiapan', 'catatan_vendor', 'last_modified_by', 'last_modified_at'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_biaya' => 'decimal:2',
        'last_modified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status_kesiapan', 'catatan_vendor', 'porsi_jumlah', 'harga_satuan', 'pj_konsumsi_user_id', 'kru_pembantu'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pjUser()
    {
        return $this->belongsTo(User::class, 'pj_konsumsi_user_id');
    }

    public function lastModifiedByUser()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function scopeTotalAnggaran(Builder $query)
    {
        return $query->sum('total_biaya');
    }
}
