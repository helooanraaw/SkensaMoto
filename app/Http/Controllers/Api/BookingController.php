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
    public function index(Request $request)
    {
        $bookings = Booking::with(['kendaraan', 'jadwal', 'progres', 'paket_servis'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil daftar booking',
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'paket_id' => 'nullable|exists:paket_servis,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        if (empty($request->paket_id) && empty($request->keluhan)) {
            return response()->json(['message' => 'Silakan pilih paket servis atau isi keluhan Anda'], 422);
        }

        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'kendaraan_id' => $kendaraan->id,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan ?? '-',
                'status' => 'pending',
            ]);

            if ($request->paket_id) {
                $booking->paket_servis()->attach($request->paket_id);
            }

            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking dibuat, menunggu persetujuan admin.',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil dibuat',
                'data' => $booking->load('kendaraan', 'paket_servis')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat booking',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
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
