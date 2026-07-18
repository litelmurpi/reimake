<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['nama_event', 'tanggal', 'deskripsi', 'status'];

    public function eventTasks()
    {
        return $this->hasMany(EventTask::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function consumptionItems()
    {
        return $this->hasMany(ConsumptionItem::class);
    }
}
