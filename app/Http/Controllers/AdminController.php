<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\JadwalHarian;
use App\Models\Inventory;
use App\Models\PaketServis;
use App\Models\ProgresServis;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $jadwalToday = JadwalHarian::where('tanggal', $today)->first();
        
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'active_bookings' => Booking::whereIn('status', ['approved', 'in_progress'])->count(),
            'inventory_low' => Inventory::where('stok', '<', 5)->count(),
        ];

        $recentBookings = Booking::with(['user', 'kendaraan', 'jadwal'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart Data: Bookings per day (Last 7 Days)
        $chartDates = [];
        $chartBookings = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartDates[] = \Carbon\Carbon::parse($date)->format('d M');
            $chartBookings[] = Booking::whereDate('created_at', $date)->count();
        }

        // Chart Data: Package Popularity
        $packages = \App\Models\PaketServis::withCount('bookings')->get();
        $packageLabels = $packages->pluck('nama_paket')->toArray();
        $packageData = $packages->pluck('bookings_count')->toArray();

        return view('admin.dashboard', compact(
            'stats', 'jadwalToday', 'recentBookings', 
            'chartDates', 'chartBookings', 
            'packageLabels', 'packageData'
        ));
    }

    public function schedules()
    {
        $schedules = JadwalHarian::orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'kendaraan', 'jadwal', 'mekanik', 'paket_servis', 'pemakaian_barang'])->orderBy('created_at', 'desc');
        
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);
        $schedules = JadwalHarian::where('tanggal', '>=', now()->toDateString())->orderBy('tanggal', 'asc')->get();
        $inventories = Inventory::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('admin.bookings.index', compact('bookings', 'schedules', 'inventories'));
    }

    public function inventory()
    {
        $items = Inventory::orderBy('nama_barang')->paginate(10);
        return view('admin.inventory.index', compact('items'));
    }

    public function services()
    {
        $packages = PaketServis::with('barang')->orderBy('nama_paket')->paginate(10);
        return view('admin.services.index', compact('packages'));
    }

    // ALGORITHM 1: Smart Slot Booking
    public function approveBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'estimasi_total_menit' => 'required|integer|min:1',
            'id_jadwal' => 'required|exists:jadwal_harian,id'
        ]);

        try {
            DB::beginTransaction();

            $jadwal = JadwalHarian::findOrFail($request->id_jadwal);
            $available_minutes = $jadwal->kapasitas_menit - $jadwal->terpakai_menit;

            if ($request->estimasi_total_menit > $available_minutes) {
                return back()->with('error', 'Kapasitas jadwal tidak mencukupi untuk estimasi pengerjaan ini.');
            }

            // Deduct capacity
            $jadwal->terpakai_menit += $request->estimasi_total_menit;
            $jadwal->save();

            // Update Booking
            $booking->update([
                'id_jadwal' => $jadwal->id,
                'estimasi_total_menit' => $request->estimasi_total_menit,
                'status' => 'approved'
            ]);

            // Add Progress
            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking disetujui, jadwal ditetapkan.'
            ]);

            DB::commit();
            return back()->with('success', 'Booking berhasil disetujui dan dijadwalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ALGORITHM 2: Auto-Deduct Inventory & Invoicing
    public function completeBooking(Request $request, Booking $booking)
    {
        if ($booking->status !== 'in_progress' || $booking->quotation_status !== 'approved') {
            return back()->with('error', 'Booking harus dalam status in_progress dan quotation disetujui.');
        }

        try {
            DB::beginTransaction();

            $total_barang = 0;

            // Dapatkan barang yang disetujui pelanggan
            $approvedItems = DB::table('pemakaian_barang')
                ->where('booking_id', $booking->id)
                ->where('is_approved', true)
                ->get();

            foreach ($approvedItems as $item) {
                $inventory = Inventory::findOrFail($item->barang_id);

                if ($inventory->stok < $item->jumlah) {
                    throw new \Exception("Stok {$inventory->nama_barang} tidak mencukupi (Sisa: {$inventory->stok}).");
                }

                // Deduct stock
                $inventory->stok -= $item->jumlah;
                $inventory->save();

                // Calculate cost
                $total_barang += ($inventory->harga_satuan * $item->jumlah);
            }

            // Calculate Jasa
            $total_jasa = $booking->paket_servis()->sum('harga_jasa');

            $booking->update([
                'total_harga' => $total_jasa + $total_barang,
                'status' => 'completed',
                'jam_selesai' => now()->format('H:i:s'),
                'nomor_invoice' => 'INV/' . date('Ymd') . '/' . str_pad($booking->id, 4, '0', STR_PAD_LEFT)
            ]);

            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Servis selesai. Menunggu pengambilan.'
            ]);

            DB::commit();
            return back()->with('success', 'Servis selesai dan invoice telah dihitung otomatis berdasarkan sparepart yang disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan servis: ' . $e->getMessage());
        }
    }

    public function sendQuotation(Request $request, Booking $booking)
    {
        $request->validate([
            'catatan_kerusakan' => 'required|string',
            'barang_id' => 'array',
            'barang_id.*' => 'exists:inventory,id',
            'jumlah' => 'array',
            'jumlah.*' => 'integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $booking->update([
                'catatan_kerusakan' => $request->catatan_kerusakan,
                'quotation_status' => 'sent'
            ]);

            // Clear previous if any
            DB::table('pemakaian_barang')->where('booking_id', $booking->id)->delete();

            if ($request->has('barang_id') && count($request->barang_id) > 0) {
                foreach ($request->barang_id as $index => $itemId) {
                    DB::table('pemakaian_barang')->insert([
                        'booking_id' => $booking->id,
                        'barang_id' => $itemId,
                        'jumlah' => $request->jumlah[$index],
                        'is_approved' => true // Default true until user unchecks
                    ]);
                }
            }

            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Mekanik telah mengecek motor. Estimasi biaya & penggantian sparepart menunggu persetujuan Anda.'
            ]);

            DB::commit();
            return back()->with('success', 'Estimasi biaya berhasil dikirim ke pelanggan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengirim estimasi: ' . $e->getMessage());
        }
    }

    public function startBooking(Booking $booking)
    {
        $booking->update([
            'status' => 'in_progress', 
            'jam_mulai' => now()->format('H:i:s'),
            'mekanik_id' => \Illuminate\Support\Facades\Auth::id()
        ]);
        ProgresServis::create(['booking_id' => $booking->id, 'status_log' => 'Mekanik mulai mengerjakan motor.']);
        return back()->with('success', 'Servis dimulai.');
    }

    public function rejectBooking(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
        ProgresServis::create(['booking_id' => $booking->id, 'status_log' => 'Booking ditolak oleh admin.']);
        return back()->with('success', 'Booking ditolak.');
    }

    // --- INVENTORY CRUD ---
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|integer|min:0',
        ]);
        Inventory::create($validated);
        return back()->with('success', 'Barang berhasil ditambahkan.');
    }

    public function updateInventory(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|integer|min:0',
        ]);
        $inventory->update($validated);
        return back()->with('success', 'Data barang berhasil diubah.');
    }

    public function destroyInventory(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }

    // --- SERVICES CRUD ---
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_menit' => 'required|integer|min:1',
            'harga_jasa' => 'required|integer|min:0',
        ]);
        PaketServis::create($validated);
        return back()->with('success', 'Paket Servis berhasil ditambahkan.');
    }

    public function updateService(Request $request, PaketServis $paketServis)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_menit' => 'required|integer|min:1',
            'harga_jasa' => 'required|integer|min:0',
        ]);
        $paketServis->update($validated);
        return back()->with('success', 'Data Paket Servis berhasil diubah.');
    }

    public function destroyService(PaketServis $paketServis)
    {
        $paketServis->delete();
        return back()->with('success', 'Paket Servis berhasil dihapus.');
    }

    // --- SCHEDULES CRUD ---
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:jadwal_harian,tanggal',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'kapasitas_menit' => 'required|integer|min:1',
        ]);
        JadwalHarian::create($validated);
        return back()->with('success', 'Jadwal operasional berhasil diset.');
    }

    public function destroySchedule(JadwalHarian $jadwalHarian)
    {
        $jadwalHarian->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function settings()
    {
        $setting = Setting::first();
        return view('admin.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'nama_bengkel' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'nomor_telepon' => 'nullable|string|max:20',
            'email_bengkel' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $data = $request->only(['nama_bengkel', 'alamat', 'nomor_telepon', 'email_bengkel']);

        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $data['logo_path'] = 'images/' . $imageName;
        }

        $setting->fill($data);
        $setting->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name', 'asc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,guru,mekanik,user'
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi " . ucfirst($request->role) . ".");
    }
}
