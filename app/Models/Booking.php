<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Nama tabel di database
    protected $table = 'booking';

    // Kolom-kolom yang boleh diisi lewat form (Mass Assignment)
    protected $fillable = [
        'user_id',            // ID Pemilik Motor
        'kendaraan_id',       // ID Motor yang mau diservis
        'id_jadwal',          // ID Jadwal bengkel yang dipilih
        'tanggal',            // Tanggal booking
        'keluhan',            // Masalah yang dirasain pelanggan
        'estimasi_total_menit', // Berapa lama kira-kira pengerjaannya
        'jam_mulai',          // Jam mulai dikerjain mekanik
        'jam_selesai',        // Jam kelar servis
        'total_harga',        // Total biaya (jasa + sparepart)
        'status',             // Status servis (pending, approved, in_progress, dll)
        'catatan_kerusakan',  // Temuan kerusakan dari mekanik
        'quotation_status',   // Persetujuan biaya dari pelanggan
        'mekanik_id',         // ID Mekanik yang ngerjain
        'nomor_invoice',      // Nomor nota/tagihan unik
    ];

    // Relasi: Mengetahui siapa pelanggan yang melakukan booking ini
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Mengetahui mekanik mana yang ngerjain servis ini
    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id');
    }

    // Relasi: Mengetahui motor mana yang sedang diservis
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    // Relasi: Mengetahui jadwal operasional bengkel untuk booking ini
    public function jadwal()
    {
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal');
    }

    // Relasi: Melihat riwayat/progres dari awal booking sampe selesai
    public function progres()
    {
        return $this->hasMany(ProgresServis::class);
    }

    // Relasi: Daftar paket servis apa aja yang diambil (misal: servis ringan + ganti oli)
    public function paket_servis()
    {
        return $this->belongsToMany(PaketServis::class, 'booking_detail', 'booking_id', 'paket_id');
    }

    // Relasi: Daftar sparepart atau barang yang dipake selama servis
    public function pemakaian_barang()
    {
        return $this->belongsToMany(Inventory::class, 'pemakaian_barang', 'booking_id', 'barang_id')
            ->withPivot('id', 'jumlah', 'is_approved');
    }
}
