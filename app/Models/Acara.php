<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acara extends Model
{
    use HasFactory;

    protected $table = 'acaras'; 

    protected $fillable = [
        'judul_acara',
        'deskripsi',
        'lokasi',
        'start_time',
        'end_time',
        'created_by',
        'is_active',
        'ketuplak',    // ditambahkan
        'foto',        // ditambahkan
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Kehadiran::class, 'event_id');
    }

    public function isInProgress()
    {
        $now = now();
        return $this->is_active && $now->between($this->start_time, $this->end_time);
    }
}
