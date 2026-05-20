<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    // Get upcoming schedules from database (today and next 4 days)
    $schedules = \App\Models\JadwalHarian::where('tanggal', '>=', \Carbon\Carbon::today())
        ->orderBy('tanggal', 'asc')
        ->take(5)
        ->get();
        
    $packages = \App\Models\PaketServis::withCount('bookings')->get();
    
    // Temukan jumlah terbanyak (minimal 1 agar tidak semua dapat tag jika 0 semua)
    $maxBookings = $packages->max('bookings_count') ?: 1;

    // Get active bookings for these dates to show in landing page
    $dates = $schedules->pluck('tanggal');
    $bookingsQuery = \App\Models\Booking::whereIn('tanggal', $dates)
        ->whereNotIn('status', ['rejected', 'cancelled']) // only active
        ->with(['kendaraan', 'paket_servis'])
        ->get();
        
    $publicBookings = $bookingsQuery->groupBy('tanggal')->map(function($dayBookings) {
        return $dayBookings->map(function($b) {
            $platParts = explode(' ', $b->kendaraan->plat_nomor);
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route for dashboard redirection based on role
    Route::get('/dashboard', function () {
        if (in_array(auth()->user()->role, ['superadmin', 'admin', 'mekanik'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // USER ROUTES
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::post('/kendaraan', [UserController::class, 'storeKendaraan'])->name('kendaraan.store');
        Route::put('/kendaraan/{kendaraan}', [UserController::class, 'updateKendaraan'])->name('kendaraan.update');
        Route::delete('/kendaraan/{kendaraan}', [UserController::class, 'destroyKendaraan'])->name('kendaraan.destroy');
        Route::post('/booking', [UserController::class, 'storeBooking'])->name('booking.store');
        Route::patch('/booking/{booking}/approve-quotation', [UserController::class, 'approveQuotation'])->name('booking.approve_quotation');
        Route::get('/booking/{booking}/invoice', [UserController::class, 'downloadInvoice'])->name('booking.invoice');
        
        Route::get('/history', [UserController::class, 'history'])->name('history');
        
        Route::get('/settings', [UserController::class, 'settings'])->name('settings.index');
        Route::post('/settings', [UserController::class, 'updateSettings'])->name('settings.update');
        
        Route::get('/api/packages', [UserController::class, 'getPackages'])->name('api.packages');
    });

    // ADMIN / WORKSHOP ROUTES
    Route::middleware('role:superadmin,admin,mekanik')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules.index');
        
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings.index');
        Route::patch('/bookings/{booking}/approve', [AdminController::class, 'approveBooking'])->name('bookings.approve')->middleware('role:superadmin,admin');
        Route::patch('/bookings/{booking}/start', [AdminController::class, 'startBooking'])->name('bookings.start');
        Route::post('/bookings/{booking}/send-quotation', [AdminController::class, 'sendQuotation'])->name('bookings.send_quotation');
        Route::patch('/bookings/{booking}/complete', [AdminController::class, 'completeBooking'])->name('bookings.complete');
        Route::patch('/bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])->name('bookings.reject')->middleware('role:superadmin,admin');
        
        Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory.index');
        Route::post('/inventory', [AdminController::class, 'storeInventory'])->name('inventory.store')->middleware('role:superadmin,admin');
        Route::put('/inventory/{inventory}', [AdminController::class, 'updateInventory'])->name('inventory.update')->middleware('role:superadmin,admin');
        Route::delete('/inventory/{inventory}', [AdminController::class, 'destroyInventory'])->name('inventory.destroy')->middleware('role:superadmin,admin');

        Route::get('/services', [AdminController::class, 'services'])->name('services.index');
        Route::post('/services', [AdminController::class, 'storeService'])->name('services.store')->middleware('role:superadmin,admin');
        Route::put('/services/{paketServis}', [AdminController::class, 'updateService'])->name('services.update')->middleware('role:superadmin,admin');
        Route::delete('/services/{paketServis}', [AdminController::class, 'destroyService'])->name('services.destroy')->middleware('role:superadmin,admin');

        Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store')->middleware('role:superadmin,admin');
        Route::delete('/schedules/{jadwalHarian}', [AdminController::class, 'destroySchedule'])->name('schedules.destroy')->middleware('role:superadmin,admin');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index')->middleware('role:superadmin');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update')->middleware('role:superadmin');

        Route::get('/users', [AdminController::class, 'users'])->name('users.index')->middleware('role:superadmin');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update_role')->middleware('role:superadmin');
    });
});

require __DIR__.'/auth.php';
