<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('plat_nomor')->unique();
            $table->string('merk');
            $table->string('tipe');
            $table->integer('tahun');
            $table->timestamps();
        });

        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->integer('stok')->default(0);
            $table->integer('harga_satuan');
            $table->timestamps();
        });

        Schema::create('paket_servis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_paket');
            $table->text('deskripsi');
            $table->integer('estimasi_menit');
            $table->integer('harga_jasa');
            $table->timestamps();
        });

        Schema::create('jadwal_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->integer('kapasitas_menit');
            $table->integer('terpakai_menit')->default(0);
            $table->timestamps();
        });

        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kendaraan_id')->constrained('kendaraan');
            $table->foreignId('id_jadwal')->nullable()->constrained('jadwal_harian');
            $table->date('tanggal');
            $table->text('keluhan');
            $table->integer('estimasi_total_menit')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->integer('total_harga')->default(0);
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('progres_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->string('status_log');
            $table->timestamps();
        });

        Schema::create('booking_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket_servis')->cascadeOnDelete();
        });

        Schema::create('paket_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_servis')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('inventory')->cascadeOnDelete();
            $table->integer('jumlah');
        });

        Schema::create('pemakaian_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('inventory')->cascadeOnDelete();
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_barang');
        Schema::dropIfExists('paket_barang');
        Schema::dropIfExists('booking_detail');
        Schema::dropIfExists('progres_servis');
        Schema::dropIfExists('booking');
        Schema::dropIfExists('jadwal_harian');
        Schema::dropIfExists('paket_servis');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('kendaraan');
    }
};
