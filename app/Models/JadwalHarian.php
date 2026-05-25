<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    // Nama table di database buat ngatur jadwal operasional bengkel
    protected $table = 'jadwal_harian';

    // Field/column yang bisa diisi lewat form (Mass Assignment)
    protected $fillable = [
        'tanggal',          // Tanggal operasional bengkel buka
        'jam_buka',         // Jam operasional bengkel mulai buka
        'jam_tutup',        // Jam operasional bengkel tutup
        'kapasitas_menit',   // Batas total waktu kerja mekanik dalam sehari (dalam menit)
        'terpakai_menit',    // Total waktu yang sudah dipakai buat servis (dalam menit)
    ];

    // Relation buat liat daftar booking/pesanan servis yang terdaftar di jadwal hari ini
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_jadwal');
    }
}

