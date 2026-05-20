<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Inventory;
use App\Models\PaketServis;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin Account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@skensa.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        // Create some initial inventory items
        Inventory::create([
            'nama_barang' => 'Oli Mesin MPX 2',
            'satuan' => 'Botol',
            'stok' => 50,
            'harga_satuan' => 45000,
        ]);
        
        Inventory::create([
            'nama_barang' => 'Busi Denso',
            'satuan' => 'Pcs',
            'stok' => 20,
            'harga_satuan' => 15000,
        ]);

        Inventory::create([
            'nama_barang' => 'Kampas Rem Depan',
            'satuan' => 'Set',
            'stok' => 15,
            'harga_satuan' => 35000,
        ]);

        // Create some initial service packages
        PaketServis::create([
            'nama_paket' => 'Servis Berkala (Tune Up)',
            'deskripsi' => 'Paket pemeriksaan rutin mulai dari pembersihan karburator/injeksi, cek busi, setel klep, hingga cek kelistrikan layaknya SOP AHASS.',
            'estimasi_menit' => 45,
            'harga_jasa' => 50000,
        ]);

        PaketServis::create([
            'nama_paket' => 'Servis Area CVT (Matic)',
            'deskripsi' => 'Solusi motor matic yang tarikannya berat atau bergetar (gredek). Kami bersihkan dan beri pelumas ulang (grease) pada area transmisi.',
            'estimasi_menit' => 60,
            'harga_jasa' => 40000,
        ]);
    }
}
