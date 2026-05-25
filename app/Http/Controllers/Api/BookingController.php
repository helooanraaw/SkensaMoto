<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kendaraan;
use App\Models\ProgresServis;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // Function buat mengambil daftar riwayat booking milik user yang sedang login
    public function index(Request $request)
    {
        // Ambil data booking beserta relasi motor, jadwal, log progres, dan paket servisnya
        $bookings = Booking::with(['kendaraan', 'jadwal', 'progres', 'paket_servis'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc') // Urutkan dari yang paling baru dibuat
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil daftar booking',
            'data' => $bookings
        ]);
    }

    // Function buat memesan/booking servis baru via API
    public function store(Request $request)
    {
        // Validasi input booking dari user
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal' => 'required|date|after_or_equal:today', // Booking tidak boleh di tanggal yang sudah lewat
            'paket_id' => 'nullable|exists:paket_servis,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        // Pelanggan harus memilih minimal paket servis ATAU menulis keluhan kerusakan
        if (empty($request->paket_id) && empty($request->keluhan)) {
            return response()->json(['message' => 'Silakan pilih paket servis atau isi keluhan Anda'], 422);
        }

        // Cari data kendaraan pelanggan, pastikan beneran milik dia sendiri
        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        // Gunakan database transaction agar jika terjadi error, data tidak tersimpan setengah-setengah
        DB::beginTransaction();
        try {
            // Buat data booking baru di database
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'kendaraan_id' => $kendaraan->id,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan ?? '-',
                'status' => 'pending', // Status awal masih nunggu persetujuan admin
            ]);

            // Kalau user milih paket servis, hubungkan datanya ke booking ini
            if ($request->paket_id) {
                $booking->paket_servis()->attach($request->paket_id);
            }

            // Catat log progres pertama kali booking dibuat
            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking dibuat, menunggu persetujuan admin.',
            ]);

            DB::commit(); // Simpan permanen perubahan data di database

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'data' => $booking->load('kendaraan', 'paket_servis')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack(); // Batalin semua perubahan database kalau ada error
            return response()->json([
                'message' => 'Gagal membuat booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Function buat melihat rincian/detail salah satu booking
    public function show(Request $request, $id)
    {
        // Cari data booking beserta detail relasi lengkapnya, pastikan milik user yang login
        $booking = Booking::with(['kendaraan', 'jadwal', 'progres', 'paket_servis', 'pemakaian_barang', 'mekanik'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail booking',
            'data' => $booking
        ]);
    }
}

