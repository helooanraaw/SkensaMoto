<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresServis extends Model
{
    // Nama table di database buat nyatet riwayat perjalanan/progres servis
    protected $table = 'progres_servis';

    // Field/column yang bisa diisi lewat form (Mass Assignment)
    protected $fillable = [
        'booking_id', // ID Booking yang bersangkutan (nyambung ke table booking)
        'status_log', // Catatan status atau aktivitas terbaru (misal: "Mekanik mulai mengerjakan motor")
    ];

    // Relation buat mengetahui data booking/servis yang dirujuk oleh log progres ini
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

