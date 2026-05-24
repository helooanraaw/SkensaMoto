<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketServis extends Model
{
    protected $table = 'paket_servis';

    protected $fillable = [
        'nama_paket',
        'tipe',
        'deskripsi',
        'estimasi_menit',
        'harga_jasa',
        'image_path',
    ];

    public function barang()
    {
        return $this->belongsToMany(Inventory::class, 'paket_barang', 'paket_id', 'barang_id')->withPivot('jumlah');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_detail', 'paket_id', 'booking_id');
    }
}
