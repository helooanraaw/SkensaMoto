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
    // Halaman utama admin pas baru login
    public function dashboard()
    {
        // Ambil tanggal hari ini buat ngecek jadwal
        $today = now()->toDateString();
        // Cari info jadwal bengkel buat hari ini
        $jadwalToday = JadwalHarian::where('tanggal', $today)->first();
        
        // Ngitung statistik buat ditampilin di kotak-kotak dashboard atas
        $stats = [
            'total_bookings' => Booking::count(), // Semua order yang pernah masuk
            'pending_bookings' => Booking::where('status', 'pending')->count(), // Order yang nunggu dikonfirmasi
            'active_bookings' => Booking::whereIn('status', ['approved', 'in_progress'])->count(), // Order yang udah disetujui atau lagi dikerjain
            'inventory_low' => Inventory::where('stok', '<', 5)->count(), // Barang yang stoknya udah nipis banget
        ];

        // Ambil 5 booking paling baru sekalian narik relasi data (user, motor, jadwal) biar gampang ditampilin
        $recentBookings = Booking::with(['user', 'kendaraan', 'jadwal'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Nyiapin data grafik: Jumlah booking selama 7 hari terakhir
        $chartDates = [];
        $chartBookings = [];
        for ($i = 6; $i >= 0; $i--) {
            // Mundur dari 6 hari lalu sampai hari ini
            $date = now()->subDays($i)->toDateString();
            $chartDates[] = \Carbon\Carbon::parse($date)->format('d M');
            $chartBookings[] = Booking::whereDate('created_at', $date)->count(); // Hitung total booking di tanggal tersebut
        }

        // Nyiapin data grafik: Paket servis mana yang paling laku/banyak dipesan
        $packages = \App\Models\PaketServis::withCount('bookings')->get();
        $packageLabels = $packages->pluck('nama_paket')->toArray(); // Nama-nama paket
        $packageData = $packages->pluck('bookings_count')->toArray(); // Jumlah order per paket

        // Kirim semua datanya ke tampilan blade admin dashboard
        return view('admin.dashboard', compact(
            'stats', 'jadwalToday', 'recentBookings', 
            'chartDates', 'chartBookings', 
            'packageLabels', 'packageData'
        ));
    }

    // Nampilin halaman kelola jadwal buka bengkel
    public function schedules()
    {
        // Narik semua data jadwal diurutkan dari yang terbaru, dibagi 10 per halaman (paginate)
        $schedules = JadwalHarian::orderBy('tanggal', 'desc')->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    // Nampilin daftar semua pesanan servis (booking) dari user
    public function bookings(Request $request)
    {
        // Bikin query awal buat narik booking plus relasinya
        $query = Booking::with(['user', 'kendaraan', 'jadwal', 'mekanik', 'paket_servis', 'pemakaian_barang'])->orderBy('created_at', 'desc');
        
        // Kalau admin nge-filter status tertentu, tambahin filter ke query-nya
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Ambil datanya dibagi 10 per halaman
        $bookings = $query->paginate(10);
        
        // Ambil jadwal mulai hari ini ke depan (buat pilih jadwal pas konfirmasi booking)
        $schedules = JadwalHarian::where('tanggal', '>=', now()->toDateString())->orderBy('tanggal', 'asc')->get();
        // Ambil barang yang masih ada stoknya (buat pilih sparepart)
        $inventories = Inventory::where('stok', '>', 0)->orderBy('nama_barang')->get();

        return view('admin.bookings.index', compact('bookings', 'schedules', 'inventories'));
    }

    // Nampilin halaman stok barang/sparepart
    public function inventory()
    {
        // Ambil semua barang sesuai urutan abjad namanya
        $items = Inventory::orderBy('nama_barang')->paginate(10);
        return view('admin.inventory.index', compact('items'));
    }

    // Nampilin halaman daftar paket servis yang ditawarkan bengkel
    public function services()
    {
        // Ambil daftar paket servis
        $packages = PaketServis::with('barang')->orderBy('nama_paket')->paginate(10);
        return view('admin.services.index', compact('packages'));
    }

    // ALGORITHM 1: Smart Slot Booking
    // Fungsi buat nyetujuin booking dari pelanggan dan nentuin estimasi waktunya
    public function approveBooking(Request $request, Booking $booking)
    {
        // Wajib ngisi estimasi waktu pengerjaan (menit)
        $request->validate([
            'estimasi_total_menit' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction(); // Mulai transaksi database biar kalo ada error bisa di-rollback semua

            // Cek apakah udah ada jadwal bengkel di tanggal booking tersebut
            $jadwal = JadwalHarian::where('tanggal', $booking->tanggal)->first();
            
            if (!$jadwal) {
                // Kalo admin belum bikin jadwal di tanggal itu, kita buatin otomatis default 8 jam kerja (480 menit)
                $jadwal = JadwalHarian::create([
                    'tanggal' => $booking->tanggal,
                    'kapasitas_menit' => 480, 
                    'terpakai_menit' => 0,
                    'jam_buka' => '08:00:00',
                    'jam_tutup' => '16:00:00'
                ]);
            }

            // Hitung sisa waktu bengkel hari itu (kapasitas dikurangi yang udah kepakai)
            $available_minutes = $jadwal->kapasitas_menit - $jadwal->terpakai_menit;

            // Kalo waktu ngerjainnya lebih lama dari sisa waktu bengkel, tolak!
            if ($request->estimasi_total_menit > $available_minutes) {
                return back()->with('error', 'Kapasitas jadwal di tanggal tersebut tidak mencukupi untuk estimasi pengerjaan ini.');
            }

            // Potong kapasitas bengkel sesuai estimasi pengerjaan
            $jadwal->terpakai_menit += $request->estimasi_total_menit;
            $jadwal->save();

            // Update status booking jadi 'approved' (disetujui)
            $booking->update([
                'id_jadwal' => $jadwal->id,
                'estimasi_total_menit' => $request->estimasi_total_menit,
                'status' => 'approved'
            ]);

            // Catat history/progres servis
            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking disetujui, jadwal ditetapkan.'
            ]);

            DB::commit(); // Simpan permanen ke database
            return back()->with('success', 'Booking berhasil disetujui dan dijadwalkan.');
        } catch (\Exception $e) {
            DB::rollBack(); // Kalo ada error di tengah jalan, batalin semua perubahan
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    // ALGORITHM 2: Auto-Deduct Inventory & Invoicing
    // Fungsi pas motor udah kelar diservis, buat ngitung harga dan potong stok barang otomatis
    public function completeBooking(Request $request, Booking $booking)
    {
        // Pastiin statusnya emang lagi 'in_progress' alias lagi dikerjain
        if ($booking->status !== 'in_progress') {
            return back()->with('error', 'Booking harus dalam status in_progress.');
        }

        try {
            DB::beginTransaction();

            $total_barang = 0;

            // Cari barang-barang apa aja yang disetujui sama pelanggan
            $approvedItems = DB::table('pemakaian_barang')
                ->where('booking_id', $booking->id)
                ->where('is_approved', true)
                ->get();

            // Cek satu-satu barangnya
            foreach ($approvedItems as $item) {
                $inventory = Inventory::findOrFail($item->barang_id);

                // Kalo stoknya tiba-tiba kurang, batalkan dan keluarin error
                if ($inventory->stok < $item->jumlah) {
                    throw new \Exception("Stok {$inventory->nama_barang} tidak mencukupi (Sisa: {$inventory->stok}).");
                }

                // Potong stok barang di gudang/inventory
                $inventory->stok -= $item->jumlah;
                $inventory->save();

                // Itung sub-total harga barang-barang ini
                $total_barang += ($inventory->harga_satuan * $item->jumlah);
            }

            // Itung total biaya jasa dari paket yang dipilih
            $total_jasa = $booking->paket_servis()->sum('harga_jasa');

            // Update status booking jadi completed (selesai) dan buatin nomor invoice
            $booking->update([
                'total_harga' => $total_jasa + $total_barang,
                'status' => 'completed',
                'jam_selesai' => now()->format('H:i:s'),
                'nomor_invoice' => 'INV/' . date('Ymd') . '/' . str_pad($booking->id, 4, '0', STR_PAD_LEFT)
            ]);

            // Catat history
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

    // Fungsi buat ngirim estimasi biaya (Quotation) ke pelanggan biar disetujui dulu
    public function sendQuotation(Request $request, Booking $booking)
    {
        // Validasi input: wajib isi catatan kerusakan, dan list barang kalau ada
        $request->validate([
            'catatan_kerusakan' => 'required|string',
            'barang_id' => 'array',
            'barang_id.*' => 'exists:inventory,id',
            'jumlah' => 'array',
            'jumlah.*' => 'integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Update catatan kerusakan dan ubah status quotation jadi terkirim (sent)
            $booking->update([
                'catatan_kerusakan' => $request->catatan_kerusakan,
                'quotation_status' => 'sent'
            ]);

            // Hapus list barang yang sebelumnya (kalau admin update/revisi estimasinya)
            DB::table('pemakaian_barang')->where('booking_id', $booking->id)->delete();

            // Masukkin daftar barang baru yang dibutuhin buat perbaikan
            if ($request->has('barang_id') && count($request->barang_id) > 0) {
                foreach ($request->barang_id as $index => $itemId) {
                    DB::table('pemakaian_barang')->insert([
                        'booking_id' => $booking->id,
                        'barang_id' => $itemId,
                        'jumlah' => $request->jumlah[$index],
                        'is_approved' => true // Defaultnya di-setujui dulu, nanti user bisa milih mau batalin atau gak
                    ]);
                }
            }

            // Catat history
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

    // Fungsi buat nandain kalo motor lagi mulai dikerjain sama mekanik
    public function startBooking(Booking $booking)
    {
        $booking->update([
            'status' => 'in_progress', 
            'jam_mulai' => now()->format('H:i:s'),
            'mekanik_id' => \Illuminate\Support\Facades\Auth::id() // Catat siapa mekanik yang ngerjain (yang login)
        ]);
        ProgresServis::create(['booking_id' => $booking->id, 'status_log' => 'Mekanik mulai mengerjakan motor.']);
        return back()->with('success', 'Servis dimulai.');
    }

    // Fungsi buat nolak pesanan servis
    public function rejectBooking(Booking $booking)
    {
        $booking->update(['status' => 'rejected']);
        ProgresServis::create(['booking_id' => $booking->id, 'status_log' => 'Booking ditolak oleh admin.']);
        return back()->with('success', 'Booking ditolak.');
    }

    // --- CRUD INVENTORY (BAGIAN KELOLA BARANG) ---
    // Nambahin barang baru
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

    // Update data barang yang udah ada
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

    // Hapus barang
    public function destroyInventory(Inventory $inventory)
    {
        $inventory->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }

    // --- CRUD SERVICES (BAGIAN KELOLA PAKET SERVIS) ---
    // Nambah paket servis baru
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'tipe' => 'required|in:jasa_saja,dengan_part',
            'deskripsi' => 'required|string',
            'estimasi_menit' => 'required|integer|min:1',
            'harga_jasa' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Kalau admin nge-upload foto, kita simpen ke folder public/images/services
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/services'), $imageName);
            $validated['image_path'] = 'images/services/' . $imageName;
        }

        PaketServis::create($validated);
        return back()->with('success', 'Paket Servis berhasil ditambahkan.');
    }

    // Update data paket servis
    public function updateService(Request $request, PaketServis $paketServis)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'tipe' => 'required|in:jasa_saja,dengan_part',
            'deskripsi' => 'required|string',
            'estimasi_menit' => 'required|integer|min:1',
            'harga_jasa' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Kalo ada upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama biar folder gak penuh
            if ($paketServis->image_path && file_exists(public_path($paketServis->image_path))) {
                unlink(public_path($paketServis->image_path));
            }

            // Simpen gambar baru
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/services'), $imageName);
            $validated['image_path'] = 'images/services/' . $imageName;
        }

        $paketServis->update($validated);
        return back()->with('success', 'Data Paket Servis berhasil diubah.');
    }

    // Hapus paket servis
    public function destroyService(PaketServis $paketServis)
    {
        $paketServis->delete();
        return back()->with('success', 'Paket Servis berhasil dihapus.');
    }

    // --- CRUD SCHEDULES (KELOLA JADWAL) ---
    // Nambahin jadwal hari kerja bengkel
    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date|unique:jadwal_harian,tanggal', // Gak boleh ada 2 jadwal di tanggal yg sama
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'kapasitas_menit' => 'required|integer|min:1', // Total menit jam kerja
        ]);
        JadwalHarian::create($validated);
        return back()->with('success', 'Jadwal operasional berhasil diset.');
    }

    // Hapus jadwal
    public function destroySchedule(JadwalHarian $jadwalHarian)
    {
        $jadwalHarian->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // Nampilin halaman setting identitas bengkel (nama, logo, kontak)
    public function settings()
    {
        $setting = Setting::first(); // Tarik data setting (biasanya cuma ada 1 baris di tabel ini)
        return view('admin.settings', compact('setting'));
    }

    // Update setting bengkel
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
            $setting = new Setting(); // Kalo emang datanya kosong banget, buatin baru
        }

        // Ambil data-data text
        $data = $request->only(['nama_bengkel', 'alamat', 'nomor_telepon', 'email_bengkel']);

        // Kalo admin ganti logo, simpen file aslinya
        if ($request->hasFile('logo')) {
            $imageName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $imageName);
            $data['logo_path'] = 'images/' . $imageName;
        }

        // Timpa data lama pake data baru terus save
        $setting->fill($data);
        $setting->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    // Nampilin daftar akun (user/mekanik/admin lain)
    public function users(Request $request)
    {
        $query = User::query();

        // Bisa difilter per role (misal mau liat mekanik aja)
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Urutin berdasarkan abjad nama
        $users = $query->orderBy('name', 'asc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Fungsi buat ngubah pangkat/role akun (misal dari user biasa dijadiin mekanik)
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,guru,mekanik,user'
        ]);

        // Pencegahan biar admin gak sengaja nurunin pangkat dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        // Update pangkat/role-nya
        $user->update(['role' => $request->role]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi " . ucfirst($request->role) . ".");
    }
}
