<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\WorkshopSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $schedules = WorkshopSchedule::withCount('bookings')
            ->orderBy('date', 'asc')->get();

        $todaySchedule = WorkshopSchedule::whereDate('date', today())->first();
        $todayQuota    = $todaySchedule?->quota ?? 0;
        $totalSchedules = $schedules->count();
        $tersediaCount  = $schedules->where('status', 'tersedia')->count();

        // Booking hari ini (yang terkait jadwal hari ini)
        $todayBookings = $todaySchedule
            ? Booking::with('user')->where('workshop_schedule_id', $todaySchedule->id)
                ->whereIn('status', ['menunggu', 'dikonfirmasi', 'selesai'])
                ->orderByDesc('created_at')->get()
            : collect();

        // Semua booking + relasi untuk panel admin
        $allBookings = Booking::with(['user', 'schedule', 'messages'])
            ->orderByDesc('created_at')->get();

        $pendingCount = $allBookings->where('status', 'menunggu')->count();

        return view('admin.dashboard', compact(
            'schedules', 'totalSchedules', 'todayQuota',
            'tersediaCount', 'allBookings', 'pendingCount',
            'todaySchedule', 'todayBookings'
        ));
    }

    // ─── CRUD Jadwal ────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'quota'      => 'required|integer|min:0|max:100',
            'status'     => 'required|in:tersedia,penuh,libur',
        ]);

        WorkshopSchedule::updateOrCreate(
            ['date' => $request->date],
            [
                'start_time' => $request->status === 'libur' ? null : $request->start_time,
                'end_time'   => $request->status === 'libur' ? null : $request->end_time,
                'quota'      => $request->status === 'libur' ? 0 : $request->quota,
                'status'     => $request->status,
            ]
        );
        return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil disimpan!');
    }

    public function update(Request $request, WorkshopSchedule $schedule)
    {
        $request->validate([
            'date'       => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'quota'      => 'required|integer|min:0|max:100',
            'status'     => 'required|in:tersedia,penuh,libur',
        ]);

        $schedule->update([
            'date'       => $request->date,
            'start_time' => $request->status === 'libur' ? null : $request->start_time,
            'end_time'   => $request->status === 'libur' ? null : $request->end_time,
            'quota'      => $request->status === 'libur' ? 0 : $request->quota,
            'status'     => $request->status,
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Jadwal diperbarui!');
    }

    public function destroy(WorkshopSchedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Jadwal dihapus!');
    }

    // ─── Manajemen Booking ──────────────────────────────────
    public function approveBooking(Booking $booking)
    {
        $booking->update(['status' => 'dikonfirmasi', 'rejection_reason' => null]);

        // Kirim pesan otomatis ke user
        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => Auth::id(),
            'is_admin'   => true,
            'message'    => '✅ Booking Anda telah dikonfirmasi! Silakan datang sesuai jadwal.',
        ]);

        return back()->with('success', 'Booking dikonfirmasi!');
    }

    public function rejectBooking(Request $request, Booking $booking)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        // Kembalikan kuota
        $booking->schedule?->increment('quota');

        $booking->update([
            'status'           => 'batal',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Kirim pesan otomatis ke user
        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => Auth::id(),
            'is_admin'   => true,
            'message'    => '❌ Booking Anda ditolak. Alasan: ' . $request->rejection_reason,
        ]);

        return back()->with('success', 'Booking ditolak dan user sudah diberitahu.');
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:menunggu,dikonfirmasi,selesai,batal']);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Status servis diperbarui!');
    }

    // ─── Chat Admin ─────────────────────────────────────────
    public function sendMessage(Request $request, Booking $booking)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => Auth::id(),
            'is_admin'   => true,
            'message'    => $request->message,
        ]);

        return back();
    }

    // Halaman detail booking (admin view)
    public function bookingDetail(Booking $booking)
    {
        $booking->load(['user', 'schedule', 'messages.sender']);
        $booking->messages()->where('is_admin', false)->update(['is_read' => true]);
        return view('admin.booking-detail', compact('booking'));
    }
}
