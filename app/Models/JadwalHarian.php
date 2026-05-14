<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    protected $table = 'jadwal_harian';

    protected $fillable = [
        'tanggal',
        'jam_buka',
        'jam_tutup',
        'kapasitas_menit',
        'terpakai_menit',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_jadwal');
    }
}
