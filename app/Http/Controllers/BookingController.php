<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Models\WorkshopSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Dashboard user
    public function dashboard()
    {
        $bookings = Booking::with(['schedule', 'messages'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        $schedules = WorkshopSchedule::where('status', 'tersedia')
            ->where('quota', '>', 0)
            ->where('date', '>=', today())
            ->orderBy('date')
            ->get();

        return view('user.dashboard', compact('bookings', 'schedules'));
    }

    // Simpan booking baru
    public function store(Request $request)
    {
        $request->validate([
            'workshop_schedule_id' => 'required|exists:workshop_schedules,id',
            'motorcycle_type'      => 'required|string|max:100',
            'license_plate'        => 'required|string|max:20',
            'complaint'            => 'nullable|string|max:500',
        ]);

        $schedule = WorkshopSchedule::findOrFail($request->workshop_schedule_id);

        // Cek kuota
        if ($schedule->quota <= 0 || $schedule->status !== 'tersedia') {
            return back()->with('error', 'Maaf, jadwal ini sudah penuh atau tidak tersedia.');
        }

        // Cek jika user sudah booking di jadwal yang sama
        $existing = Booking::where('user_id', Auth::id())
            ->where('workshop_schedule_id', $schedule->id)
            ->whereNotIn('status', ['batal'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki booking di jadwal ini.');
        }

        $booking = Booking::create([
            'user_id'              => Auth::id(),
            'workshop_schedule_id' => $schedule->id,
            'motorcycle_type'      => $request->motorcycle_type,
            'license_plate'        => strtoupper($request->license_plate),
            'complaint'            => $request->complaint,
            'status'               => 'menunggu',
        ]);

        // Kurangi kuota
        $schedule->decrement('quota');

        return redirect()->route('user.dashboard')->with('success', 'Booking berhasil! Menunggu konfirmasi admin.');
    }

    // Batalkan booking oleh user
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        if (in_array($booking->status, ['selesai', 'batal'])) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        // Kembalikan kuota
        $booking->schedule?->increment('quota');
        $booking->update(['status' => 'batal']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    // Kirim pesan (user)
    public function sendMessage(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);
        $request->validate(['message' => 'required|string|max:1000']);

        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => Auth::id(),
            'is_admin'   => false,
            'message'    => $request->message,
        ]);

        return back();
    }

    // Halaman detail booking + chat
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) abort(403);

        $booking->load(['schedule', 'messages.sender']);
        // Tandai pesan admin sebagai sudah dibaca
        $booking->messages()->where('is_admin', true)->update(['is_read' => true]);

        return view('user.booking-detail', compact('booking'));
    }
}
