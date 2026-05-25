<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketServis extends Model
{
    // Nama table di database yang menyimpan daftar paket servis yang ditawarkan
    protected $table = 'paket_servis';

    // Field/column yang bisa diisi lewat form (Mass Assignment)
    protected $fillable = [
        'nama_paket',     // Nama paket servis (misal: Servis Ringan, Ganti Oli, dll)
        'tipe',           // Tipe paket (jasa_saja atau dengan_part)
        'deskripsi',      // Detail penjelasan apa saja yang dikerjakan di paket ini
        'estimasi_menit', // Perkiraan waktu pengerjaan dalam hitungan menit
        'harga_jasa',     // Biaya jasa untuk pengerjaan paket servis ini
        'image_path',     // Lokasi file gambar ikon/foto paket servis
    ];

    // Relation buat mengetahui sparepart atau barang apa saja yang sepaket/dipakai di paket servis ini
    public function barang()
    {
        return $this->belongsToMany(Inventory::class, 'paket_barang', 'paket_id', 'barang_id')->withPivot('jumlah');
    }

    // Relation buat mengetahui pesanan booking mana saja yang mengambil paket servis ini
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_detail', 'paket_id', 'booking_id');
    }
}

