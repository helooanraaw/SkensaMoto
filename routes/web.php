<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// ─── Landing Page ────────────────────────────────────────
Route::get('/', function () {
    $now    = \Carbon\Carbon::now('Asia/Makassar');
    $monday = $now->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $friday = $monday->copy()->addDays(4);

    $schedules = \App\Models\WorkshopSchedule::whereBetween('date', [
        $monday->format('Y-m-d'), $friday->format('Y-m-d')
    ])->orderBy('date', 'asc')->get()->keyBy('date');

    $weekDays = collect();
    for ($i = 0; $i < 5; $i++) {
        $date = $monday->copy()->addDays($i)->format('Y-m-d');
        $weekDays->push($schedules->get($date));
    }

    return view('welcome', ['schedules' => $weekDays]);
});

// ─── User Dashboard & Booking ────────────────────────────
Route::middleware(['auth'])->group(function () {
    // Dashboard user (redirect dari /dashboard)
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');

    // Booking
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/messages', [BookingController::class, 'sendMessage'])->name('bookings.messages.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin ───────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Jadwal
    Route::post('/schedules', [\App\Http\Controllers\AdminController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{schedule}', [\App\Http\Controllers\AdminController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('schedules.destroy');

    // Booking management
    Route::patch('/bookings/{booking}/approve', [\App\Http\Controllers\AdminController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [\App\Http\Controllers\AdminController::class, 'rejectBooking'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/status', [\App\Http\Controllers\AdminController::class, 'updateBookingStatus'])->name('bookings.status');

    // Chat admin
    Route::post('/bookings/{booking}/messages', [\App\Http\Controllers\AdminController::class, 'sendMessage'])->name('bookings.messages.send');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\AdminController::class, 'bookingDetail'])->name('bookings.show');
});

require __DIR__.'/auth.php';
