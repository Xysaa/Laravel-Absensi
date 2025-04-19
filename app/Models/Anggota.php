<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model // atau Anggota jika Anda mengubah nama
{
    use HasFactory;
    protected $table = 'anggotas'; // Pastikan nama tabel benar
    protected $fillable = [
        'nim',
        'nama',
        'status_anggota',
        'angkatan',
    ];

    public function attendances()
    {
        return $this->hasMany(Kehadiran::class,'student_id'); 
    }

    public function hasAttendedEvent($eventId)
    {
        return $this->attendances()->where('event_id', $eventId)->exists();
    }
}