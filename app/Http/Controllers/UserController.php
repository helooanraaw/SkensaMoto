<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kendaraan;
use App\Models\JadwalHarian;
use App\Models\PaketServis;
use App\Models\ProgresServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $kendaraan = Kendaraan::where('user_id', $user->id)->get();
        
        $bookings = Booking::with(['kendaraan', 'jadwal', 'progres', 'paket_servis', 'pemakaian_barang', 'mekanik'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSchedules = JadwalHarian::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('user.dashboard', compact('kendaraan', 'bookings', 'activeSchedules'));
    }

    public function storeKendaraan(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraan,plat_nomor',
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1),
        ]);

        Kendaraan::create([
            'user_id' => Auth::id(),
            'plat_nomor' => strtoupper($request->plat_nomor),
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
        ]);

        return back()->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'paket_id' => 'nullable|exists:paket_servis,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        if (empty($request->paket_id) && empty($request->keluhan)) {
            return back()->with('error', 'Silakan pilih paket servis atau isi keluhan Anda.');
        }

        // Cek kendaraan milik user
        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)->where('user_id', Auth::id())->firstOrFail();

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'kendaraan_id' => $kendaraan->id,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan ?? '-',
                'status' => 'pending',
            ]);

            if ($request->paket_id) {
                // Attach paket to booking
                $booking->paket_servis()->attach($request->paket_id);
            }

            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking dibuat, menunggu persetujuan admin.',
            ]);

            DB::commit();
            return back()->with('success', 'Booking berhasil dibuat. Silakan tunggu konfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat membuat booking.');
        }
    }

    public function approveQuotation(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'approved_items' => 'array',
        ]);

        $approvedIds = $request->approved_items ?? [];

        // Set is_approved status for all items in this booking
        $items = \DB::table('pemakaian_barang')->where('booking_id', $booking->id)->get();
        
        foreach ($items as $item) {
            \DB::table('pemakaian_barang')
                ->where('id', $item->id)
                ->update(['is_approved' => in_array($item->id, $approvedIds)]);
        }

        $booking->update([
            'quotation_status' => 'approved'
        ]);

        ProgresServis::create([
            'booking_id' => $booking->id,
            'status_log' => 'Pelanggan telah menyetujui estimasi biaya dan sparepart.'
        ]);

        return back()->with('success', 'Persetujuan biaya berhasil dikirim ke bengkel.');
    }

    public function downloadInvoice(\App\Models\Booking $booking)
    {
        // Pastikan hanya pemilik yang bisa mendownload
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $booking->load(['kendaraan', 'jadwal', 'mekanik', 'pemakaian_barang', 'paket_servis']);
        $appSetting = \App\Models\Setting::first();

        $filename = 'Invoice_MotoSkensa_' . str_replace(['/', '\\'], '-', $booking->nomor_invoice) . '.pdf';
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('booking', 'appSetting'));
        return $pdf->download($filename);
    }

    public function history()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->where('status', 'selesai')
            ->with(['kendaraan', 'paket_servis', 'mekanik'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('user.history', compact('bookings'));
    }

    public function settings()
    {
        return view('user.settings', ['user' => Auth::user()]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function getPackages()
    {
        $packages = PaketServis::orderBy('harga_jasa', 'asc')->get();
        return response()->json($packages);
    }
}
