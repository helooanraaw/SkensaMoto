<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'id_jadwal',
        'tanggal',
        'keluhan',
        'estimasi_total_menit',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
        'catatan_kerusakan',
        'quotation_status',
        'mekanik_id',
        'nomor_invoice',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal');
    }

    public function progres()
    {
        return $this->hasMany(ProgresServis::class);
    }

    public function paket_servis()
    {
        return $this->belongsToMany(PaketServis::class, 'booking_detail', 'booking_id', 'paket_id');
    }

    public function pemakaian_barang()
    {
        return $this->belongsToMany(Inventory::class, 'pemakaian_barang', 'booking_id', 'barang_id')
            ->withPivot('id', 'jumlah', 'is_approved');
    }
}
