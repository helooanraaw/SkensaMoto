<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    // Function buat mengambil semua data motor milik user yang sedang login via API
    public function index(Request $request)
    {
        // Cari daftar kendaraan berdasarkan ID user yang login
        $kendaraan = Kendaraan::where('user_id', $request->user()->id)->get();
        
        return response()->json([
            'message' => 'Berhasil mengambil data kendaraan',
            'data' => $kendaraan
        ]);
    }

    // Function buat mendaftarkan motor baru via API
    public function store(Request $request)
    {
        // Validasi input data motor
        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraan,plat_nomor', // Plat motor gak boleh kembar di database
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1), // Batas tahun motor dari 1990 sampai tahun depan
        ]);

        // Simpan data motor baru
        $kendaraan = Kendaraan::create([
            'user_id' => $request->user()->id,
            'plat_nomor' => strtoupper($request->plat_nomor), // Plat motor diubah jadi huruf kapital semua otomatis
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
        ]);

        return response()->json([
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => $kendaraan
        ], 201);
    }

    // Function buat menghapus data motor via API
    public function destroy(Request $request, $id)
    {
        // Cari data motor, pastikan beneran milik user yang login
        $kendaraan = Kendaraan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        // Kalo motor gak ketemu atau bukan milik dia
        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        // Hapus datanya
        $kendaraan->delete();

        return response()->json([
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }
}

