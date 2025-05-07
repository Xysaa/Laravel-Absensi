<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
   use HasFactory;
   protected $table = 'qrcode';
   protected $primaryKey = 'id';

   protected $fillable = [
      'qrcode', 'anggota_id'
   ];

   protected $hidden = [
      'created_at', 'updated_at'
   ];

   public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }



}