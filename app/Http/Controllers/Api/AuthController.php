<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Function buat pendaftaran akun baru via API
    public function register(Request $request)
    {
        // Validasi data input pendaftaran
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // password wajib dicocokkan dengan password_confirmation
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        // Simpan data user baru ke database, default role sebagai 'user'
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password biar aman
            'role' => 'user',
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        // Buat token akses (Sanctum) biar user langsung otomatis login setelah daftar
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kirim respon sukses beserta token akses dalam format JSON
        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // Function buat proses login via API
    public function login(Request $request)
    {
        // Validasi input email dan password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari user berdasarkan email yang dimasukkan
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ada dan passwordnya cocok
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial tidak valid' // Login gagal
            ], 401);
        }

        // Bikin token akses baru karena login berhasil
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kirim data user dan token aksesnya
        return response()->json([
            'message' => 'Login berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Function buat proses logout/hapus token akses via API
    public function logout(Request $request)
    {
        // Hapus token akses yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}

