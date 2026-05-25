<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    // Nama table di database yang nyimpen data stok sparepart atau barang
    protected $table = 'inventory';

    // Field/column yang bisa diisi lewat form (Mass Assignment)
    protected $fillable = [
        'nama_barang',  // Nama sparepart atau barang (misal: Oli Mesin, Kampas Rem)
        'satuan',       // Satuan barang (misal: Botol, Pcs, Lembar)
        'stok',         // Jumlah stok yang tersedia di gudang
        'harga_satuan', // Harga jual per satu barang/sparepart
    ];

    // Relation buat nyari tahu sparepart ini terhubung ke paket servis mana saja
    public function paket_servis()
    {
        return $this->belongsToMany(PaketServis::class, 'paket_barang', 'barang_id', 'paket_id')->withPivot('jumlah');
    }

    // Relation buat nyari tahu riwayat pemakaian sparepart ini di booking/servis yang mana saja
    public function pemakaian_booking()
    {
        return $this->belongsToMany(Booking::class, 'pemakaian_barang', 'barang_id', 'booking_id')->withPivot('jumlah');
    }
}

