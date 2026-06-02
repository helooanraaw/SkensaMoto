<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman depan (Landing Page) yang dilihat orang pas baru buka web
Route::get('/', function () {
    // Ambil jadwal bengkel buat hari ini sampe 5 hari ke depan
    $schedules = \App\Models\JadwalHarian::where('tanggal', '>=', \Carbon\Carbon::today())
        ->orderBy('tanggal', 'asc')
        ->take(5)
        ->get();
        
    // Ambil data paket servis sekalian hitung berapa kali paket itu pernah dipesan
    $packages = \App\Models\PaketServis::withCount('bookings')->get();
    
    // Cari angka pesanan terbanyak buat nentuin paket mana yang paling populer
    $maxBookings = $packages->max('bookings_count') ?: 1;

    // Ambil daftar antrean servis buat ditampilin di halaman depan (biar orang tau status motornya)
    $dates = $schedules->pluck('tanggal');
    $bookingsQuery = \App\Models\Booking::whereIn('tanggal', $dates)
        ->whereNotIn('status', ['rejected', 'cancelled']) // cuma ambil yang gak ditolak/batal
        ->with(['kendaraan', 'paket_servis'])
        ->get();
        
    // Kelompokkan data antrean berdasarkan tanggal dan samarkan plat nomornya buat privasi
    $publicBookings = $bookingsQuery->groupBy('tanggal')->map(function($dayBookings) {
        return $dayBookings->map(function($b) {
            $platParts = explode(' ', $b->kendaraan->plat_nomor);
            // Kalo platnya lengkap, bagian tengahnya disensor pake bintang
            $maskedPlat = count($platParts) >= 3 ? $platParts[0] . ' *** ' . end($platParts) : (strlen($b->kendaraan->plat_nomor) > 4 ? substr($b->kendaraan->plat_nomor, 0, 2) . ' *** ' . substr($b->kendaraan->plat_nomor, -2) : '***');
            
            return [
                'id' => $b->id,
                'status' => $b->status,
                'kendaraan' => $b->kendaraan->merk . ' ' . $b->kendaraan->tipe,
                'plat_nomor_masked' => $maskedPlat,
                'paket' => $b->paket_servis->count() > 0 ? $b->paket_servis->pluck('nama_paket')->implode(', ') : 'Servis Umum',
            ];
        });
    });

    return view('welcome', compact('schedules', 'packages', 'maxBookings', 'publicBookings'));
});

// Kumpulan rute yang cuma bisa diakses kalo udah login (Middleware Auth)
Route::middleware('auth')->group(function () {
    
    // Rute buat kelola profil sendiri (edit nama, email, hapus akun)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute pengalih dashboard: biar admin ke halaman admin, user biasa ke halaman user
    Route::get('/dashboard', function () {
        if (in_array(auth()->user()->role, ['superadmin', 'admin', 'mekanik'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // --- BAGIAN KHUSUS PELANGGAN (USER) ---
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard'); // Dashboard user
        
        // Kelola data motor pelanggan
        Route::post('/kendaraan', [UserController::class, 'storeKendaraan'])->name('kendaraan.store');
        Route::put('/kendaraan/{kendaraan}', [UserController::class, 'updateKendaraan'])->name('kendaraan.update');
        Route::delete('/kendaraan/{kendaraan}', [UserController::class, 'destroyKendaraan'])->name('kendaraan.destroy');
        
        // Proses booking servis
        Route::post('/booking', [UserController::class, 'storeBooking'])->name('booking.store');
        Route::patch('/booking/{booking}/approve-quotation', [UserController::class, 'approveQuotation'])->name('booking.approve_quotation'); // Setujuin biaya sparepart
        Route::get('/booking/{booking}/invoice', [UserController::class, 'downloadInvoice'])->name('booking.invoice'); // Download invoice PDF
        
        Route::get('/history', [UserController::class, 'history'])->name('history'); // Liat riwayat servis lama
        
        Route::get('/settings', [UserController::class, 'settings'])->name('settings.index'); // Halaman pengaturan profil
        Route::post('/settings', [UserController::class, 'updateSettings'])->name('settings.update'); // Simpan perubahan profil
        
        Route::get('/api/packages', [UserController::class, 'getPackages'])->name('api.packages'); // Ambil data paket via API buat JavaScript
    });

    // --- BAGIAN KHUSUS ADMIN / BENGKEL (WORKSHOP) ---
    // Diatur lewat middleware 'role' biar cuma admin, superadmin, atau mekanik yang bisa masuk
    Route::middleware('role:superadmin,admin,mekanik')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules.index'); // Liat jadwal buka bengkel
        
        // Kelola antrean booking
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
        Route::patch('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve')->middleware('role:superadmin,admin'); // Acc booking baru
        Route::patch('/bookings/{booking}/start', [AdminController::class, 'startBooking'])->name('bookings.start'); // Mulai ngerjain motor
        Route::post('/bookings/{booking}/send-quotation', [AdminController::class, 'sendQuotation'])->name('bookings.send_quotation'); // Kirim rincian biaya ke user
        Route::patch('/bookings/{booking}/complete', [AdminController::class, 'completeBooking'])->name('bookings.complete'); // Tandai servis sudah kelar
        Route::patch('/bookings/{booking}/payment', [AdminController::class, 'updatePaymentStatus'])->name('bookings.update_payment'); // Ubah status pembayaran
        Route::patch('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject')->middleware('role:superadmin,admin'); // Tolak booking
        
        // Kelola stok sparepart (Inventory)
        Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory.index');
        Route::post('/inventory', [AdminController::class, 'storeInventory'])->name('inventory.store')->middleware('role:superadmin,admin');
        Route::put('/inventory/{inventory}', [AdminController::class, 'updateInventory'])->name('inventory.update')->middleware('role:superadmin,admin');
        Route::delete('/inventory/{inventory}', [AdminController::class, 'destroyInventory'])->name('inventory.destroy')->middleware('role:superadmin,admin');

        // Kelola paket servis yang ditawarkan
        Route::get('/services', [AdminController::class, 'services'])->name('services.index');
        Route::post('/services', [AdminController::class, 'storeService'])->name('services.store')->middleware('role:superadmin,admin');
        Route::put('/services/{paketServis}', [AdminController::class, 'updateService'])->name('services.update')->middleware('role:superadmin,admin');
        Route::delete('/services/{paketServis}', [AdminController::class, 'destroyService'])->name('services.destroy')->middleware('role:superadmin,admin');

        // Kelola hari kerja bengkel
        Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store')->middleware('role:superadmin,admin');
        Route::delete('/schedules/{jadwalHarian}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy')->middleware('role:superadmin,admin');

        // Pengaturan website (cuma buat Superadmin)
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index')->middleware('role:superadmin');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update')->middleware('role:superadmin');

        // Kelola daftar akun pengguna (cuma buat Superadmin)
        Route::get('/users', [AdminController::class, 'users'])->name('users.index')->middleware('role:superadmin');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update_role')->middleware('role:superadmin');
    });
});

// Muat rute bawaan buat autentikasi (login, register, logout, dsb)
require __DIR__.'/auth.php';
