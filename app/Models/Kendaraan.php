<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    // Nama table di database buat nyimpen data motor/kendaraan pelanggan
    protected $table = 'kendaraan';

    // Field/column yang bisa diisi lewat form (Mass Assignment)
    protected $fillable = [
        'user_id',    // ID pemilik motor (nyambung ke table users)
        'plat_nomor', // Nomor polisi/plat motor (misal: DK 1234 AB)
        'merk',       // Merk pabrikan motor (misal: Honda, Yamaha, Suzuki)
        'tipe',       // Tipe/seri motor (misal: Beat, Nmax, Satria)
        'tahun',      // Tahun pembuatan motor
    ];

    // Relation buat nyari tahu siapa pemilik motor ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation buat liat riwayat booking atau antrean servis yang pernah dilakukan motor ini
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

