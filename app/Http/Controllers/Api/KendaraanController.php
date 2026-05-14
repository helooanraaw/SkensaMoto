<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $kendaraan = Kendaraan::where('user_id', $request->user()->id)->get();
        
        return response()->json([
            'message' => 'Berhasil mengambil data kendaraan',
            'data' => $kendaraan
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraan,plat_nomor',
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1),
        ]);

        $kendaraan = Kendaraan::create([
            'user_id' => $request->user()->id,
            'plat_nomor' => strtoupper($request->plat_nomor),
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
        ]);

        return response()->json([
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => $kendaraan
        ], 201);
    }

    public function destroy(Request $request, $id)
    {
        $kendaraan = Kendaraan::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$kendaraan) {
            return response()->json(['message' => 'Kendaraan tidak ditemukan'], 404);
        }

        $kendaraan->delete();

        return response()->json([
            'message' => 'Kendaraan berhasil dihapus'
        ]);
    }
}
