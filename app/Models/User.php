<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Mengaktifkan fitur token API (Sanctum), factory pengisi data palsu, dan notifikasi email/sistem
    use HasApiTokens, HasFactory, Notifiable;

    // Field/column yang boleh diisi langsung lewat form (Mass Assignment)
    protected $fillable = [
        'name',          // Nama lengkap pengguna
        'email',         // Alamat email pengguna buat login
        'password',      // Kata sandi akun (yang nantinya di-hash)
        'role',          // Pangkat/peran pengguna (superadmin, admin, guru, mekanik, user)
        'nomor_telepon', // Nomor telepon atau WA pengguna
    ];

    // Field/column yang disembunyikan saat data pengguna diubah jadi JSON (misal buat API)
    protected $hidden = [
        'password',       // Kata sandi tidak boleh bocor keluar
        'remember_token', // Token pengingat login otomatis
    ];

    // Konversi tipe data otomatis saat dibaca dari database (Casting)
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Diubah jadi tipe tanggal-waktu
            'password' => 'hashed',            // Otomatis di-hash pas disimpan
        ];
    }
}

