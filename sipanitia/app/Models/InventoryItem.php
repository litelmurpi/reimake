<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;

class InventoryItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'event_id', 'nama_barang', 'divisi_peminta', 'sistem_pengadaan',
        'tgl_pinjam_beli', 'deadline_kumpul', 'tgl_kembali', 'pj_peminjam_user_id',
        'pihak_dipinjami', 'estimasi_biaya', 'posisi_barang', 'status_ceklis',
        'catatan', 'last_modified_by', 'last_modified_at'
    ];

    protected $casts = [
        'tgl_pinjam_beli' => 'date',
        'deadline_kumpul' => 'date',
        'tgl_kembali' => 'date',
        'estimasi_biaya' => 'decimal:2',
        'last_modified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status_ceklis', 'posisi_barang', 'catatan', 'estimasi_biaya', 'deadline_kumpul', 'pj_peminjam_user_id', 'pihak_dipinjami'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pjUser()
    {
        return $this->belongsTo(User::class, 'pj_peminjam_user_id');
    }

    public function lastModifiedByUser()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    public function scopeOverdue(Builder $query)
    {
        return $query->where('deadline_kumpul', '<', today())->where('status_ceklis', 'Belum Kembali');
    }

    public function scopeByPengadaan(Builder $query, $jenis)
    {
        return $query->where('sistem_pengadaan', $jenis);
    }

    public function scopeTotalEstimasiBiaya(Builder $query)
    {
        return $query->sum('estimasi_biaya');
    }
}
