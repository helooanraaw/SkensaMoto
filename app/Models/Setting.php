<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Field/column yang bisa diisi lewat form (Mass Assignment) buat informasi/profil bengkel
    protected $fillable = [
        'nama_bengkel',   // Nama bengkel (misal: MotoSkensa)
        'alamat',         // Alamat fisik bengkel
        'nomor_telepon',  // Nomor telepon/kontak bengkel
        'email_bengkel',  // Alamat email bengkel
        'logo_path',      // Path/lokasi file logo bengkel yang diupload
    ];
}

