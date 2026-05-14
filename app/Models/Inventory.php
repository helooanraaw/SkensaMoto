<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'stok',
        'harga_satuan',
    ];

    public function paket_servis()
    {
        return $this->belongsToMany(PaketServis::class, 'paket_barang', 'barang_id', 'paket_id')->withPivot('jumlah');
    }

    public function pemakaian_booking()
    {
        return $this->belongsToMany(Booking::class, 'pemakaian_barang', 'barang_id', 'booking_id')->withPivot('jumlah');
    }
}
