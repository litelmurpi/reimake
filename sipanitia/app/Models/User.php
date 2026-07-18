<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, \Spatie\Permission\Traits\HasRoles;

    public function divisis()
    {
        return $this->belongsToMany(Divisi::class)->withPivot('is_koordinator')->withTimestamps();
    }

    public function isKoordinatorOf(Divisi $divisi): bool
    {
        return $this->divisis()->where('divisi_id', $divisi->id)->wherePivot('is_koordinator', true)->exists();
    }

    public function belongsToDivisi(Divisi $divisi): bool
    {
        return $this->divisis()->where('divisi_id', $divisi->id)->exists();
    }

    public function adminTasks()
    {
        return $this->hasMany(AdminTask::class, 'pj_user_id');
    }

    public function eventTasks()
    {
        return $this->hasMany(EventTask::class, 'pj_user_id');
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'pj_peminjam_user_id');
    }

    public function consumptionItems()
    {
        return $this->hasMany(ConsumptionItem::class, 'pj_konsumsi_user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
