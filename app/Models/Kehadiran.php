<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kehadiran extends Model
{
    use HasFactory;
    protected $table = 'kehadirans';
    protected $fillable = [
        'event_id',
        'student_id',
        'check_in_time',
        'status',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    public function acara()
    {
        return $this->belongsTo(Acara::class,'event_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class,'student_id');
    }
}
