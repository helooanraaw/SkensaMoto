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
    // Fungsi buat nampilin halaman dashboard pas pelanggan (user) login
    public function dashboard()
    {
        // Ambil data siapa yang lagi login sekarang
        $user = Auth::user();
        
        // Ambil daftar motor milik user ini beserta total booking yang terikat
        $kendaraan = Kendaraan::where('user_id', $user->id)->withCount('bookings')->get();
        
        // Ambil riwayat order servis (booking) milik user ini dari yang paling baru
        // Kita juga narik data relasinya (motor, mekanik, paket, dsb) sekalian biar gampang nampilinnya
        $bookings = Booking::with(['kendaraan', 'jadwal', 'progres', 'paket_servis', 'pemakaian_barang', 'mekanik'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil jadwal bengkel mulai dari hari ini ke depan (buat form booking baru)
        $activeSchedules = JadwalHarian::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->get();

        // Kirim semua datanya ke file tampilan (blade)
        return view('user.dashboard', compact('kendaraan', 'bookings', 'activeSchedules'));
    }

    // Fungsi buat user nambahin data motornya ke sistem
    public function storeKendaraan(Request $request)
    {
        // Validasi inputan biar gak ngasal
        $request->validate([
            'plat_nomor' => 'required|string|unique:kendaraan,plat_nomor', // Plat gak boleh sama sama yg udah ada
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1), // Minimal tahun 1990
        ]);

        // Simpan kendaraan dan kaitin sama ID user yang login
        Kendaraan::create([
            'user_id' => Auth::id(),
            'plat_nomor' => strtoupper($request->plat_nomor), // Plat dibikin huruf besar semua otomatis
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
        ]);

        return back()->with('success', 'Data motor berhasil ditambahkan.');
    }

    // Fungsi buat ngubah data motor
    public function updateKendaraan(Request $request, Kendaraan $kendaraan)
    {
        // Keamanan: Cek beneran gak ini motor milik orang yang lagi login?
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403); // Kalau beda orang, lempar error forbidden
        }

        // Cari tahu apakah motor ini sudah punya riwayat booking/transaksi
        $hasBookings = $kendaraan->bookings()->exists();

        $rules = [
            'merk' => 'required|string',
            'tipe' => 'required|string',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1),
        ];

        // Pengaman histori: Kalau sudah ada transaksi/booking, plat nomor tidak boleh diganti
        if ($hasBookings) {
            $request->merge(['plat_nomor' => $kendaraan->plat_nomor]);
        } else {
            $rules['plat_nomor'] = 'required|string|unique:kendaraan,plat_nomor,' . $kendaraan->id;
        }

        $request->validate($rules);

        // Update datanya
        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'tahun' => $request->tahun,
        ]);

        return back()->with('success', 'Data motor berhasil diperbarui.');
    }

    // Fungsi buat ngapus data motor
    public function destroyKendaraan(Kendaraan $kendaraan)
    {
        // Pengecekan keamanan lagi
        if ($kendaraan->user_id !== Auth::id()) {
            abort(403);
        }

        // Kalo motor ini udah pernah diservis/dibooking, gak boleh dihapus, soalnya datanya dipake buat laporan bengkel
        if ($kendaraan->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus motor ini karena sudah memiliki riwayat servis atau antrean.');
        }

        // Kalau aman (belum pernah dibooking), baru boleh dihapus
        $kendaraan->delete();
        return back()->with('success', 'Data motor berhasil dihapus.');
    }

    // Fungsi pas user nge-submit form buat daftar antrean/booking
    public function storeBooking(Request $request)
    {
        // Pastiin data yang di-submit bener (ada ID kendaraan, ada tanggal, dsb)
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'tanggal' => 'required|date|after_or_equal:today', // Gak boleh booking buat masa lalu
            'paket_ids' => 'nullable|array',
            'paket_ids.*' => 'exists:paket_servis,id',
            'keluhan' => 'nullable|string|max:1000',
        ]);

        // Paling gak, user harus milih paket servis ATAU ngisi keluhan. Kalau kosong dua-duanya tolak.
        if (empty($request->paket_ids) && empty($request->keluhan)) {
            return back()->with('error', 'Silakan pilih paket servis atau isi keluhan Anda.');
        }

        // Keamanan: Pastiin kendaraan yang dipilih emang bener-bener milik user ini
        $kendaraan = Kendaraan::where('id', $request->kendaraan_id)->where('user_id', Auth::id())->firstOrFail();

        DB::beginTransaction(); // Buka transaksi database
        try {
            // Bikin record/data booking baru di database
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'kendaraan_id' => $kendaraan->id,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan ?? '-',
                'status' => 'pending', // Status awalnya 'pending' (nunggu di-acc admin)
            ]);

            // Kalau user milih paket-paket tertentu, kita kaitin paketnya sama booking ini
            if ($request->has('paket_ids') && is_array($request->paket_ids)) {
                $booking->paket_servis()->attach($request->paket_ids);
            }

            // Catat history booking pertamanya
            ProgresServis::create([
                'booking_id' => $booking->id,
                'status_log' => 'Booking dibuat, menunggu persetujuan admin.',
            ]);

            DB::commit(); // Kalo gak ada error, simpan permanen
            return back()->with('success', 'Booking berhasil dibuat. Silakan tunggu konfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack(); // Kalau gagal/error di tengah proses, batalin semua biar gak ada data setengah matang
            return back()->with('error', 'Terjadi kesalahan saat membuat booking.');
        }
    }


    // Fungsi buat download file struk / invoice format PDF pas servis udah kelar
    public function downloadInvoice(\App\Models\Booking $booking)
    {
        // Keamanan: Cuma pemilik booking yang boleh narik PDF-nya
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Tarik data lengkapnya
        $booking->load(['kendaraan', 'jadwal', 'mekanik', 'pemakaian_barang', 'paket_servis']);
        // Tarik setting (nama bengkel dsb buat kop surat di PDF)
        $appSetting = \App\Models\Setting::first();

        // Nyiapin nama file pas di-download, misal "Invoice_MotoSkensa_INV-2026-0001.pdf"
        $filename = 'Invoice_MotoSkensa_' . str_replace(['/', '\\'], '-', $booking->nomor_invoice) . '.pdf';
        
        // Render dari file view 'pdf.invoice' pake library DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('booking', 'appSetting'));
        return $pdf->download($filename); // Lempar file ke user buat disedot
    }

    // Nampilin halaman riwayat semua servis yang pernah dilakukan
    public function history()
    {
        // Narik semua data booking milik user ini
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['kendaraan', 'paket_servis', 'mekanik', 'progres', 'pemakaian_barang'])
            ->orderBy('tanggal', 'desc') // Paling baru ada di atas
            ->get();

        return view('user.history', compact('bookings'));
    }

    // Nampilin form kelola profil akun (nama, email, password)
    public function settings()
    {
        return view('user.settings', ['user' => Auth::user()]);
    }

    // Proses update profilnya
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Validasi, cek format email dan pastiin email belom dipake orang lain (kecuali email dia sendiri yg lama)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nomor_telepon' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed', // Kalau mau ganti password, harus sesuai ketikan "confirm password"
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nomor_telepon' => $request->nomor_telepon,
        ];

        // Kalau form password diisi, enkripsi passwordnya (di-hash) lalu masukin ke data yang mau di-update
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        // Save ke database
        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function calendar()
    {
        $bookings = Booking::with(['user', 'kendaraan', 'paket_servis'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        $bookingsJson = $bookings->map(function($b) {
            $isOwn = $b->user_id === auth()->id();
            
            // Masking name and plate number for other users
            $platParts = explode(' ', $b->kendaraan->plat_nomor);
            $maskedPlat = count($platParts) >= 3 
                ? $platParts[0] . ' *** ' . end($platParts) 
                : (strlen($b->kendaraan->plat_nomor) > 4 
                    ? substr($b->kendaraan->plat_nomor, 0, 2) . ' *** ' . substr($b->kendaraan->plat_nomor, -2) 
                    : '***');
            
            $nameParts = explode(' ', $b->user->name);
            $maskedName = count($nameParts) > 1 
                ? $nameParts[0] . ' ' . substr(end($nameParts), 0, 1) . '***' 
                : substr($b->user->name, 0, 3) . '***';

            return [
                'id' => $b->id,
                'tanggal' => $b->tanggal,
                'is_own' => $isOwn,
                'user_name' => $isOwn ? $b->user->name : $maskedName,
                'kendaraan' => $isOwn ? $b->kendaraan->merk . ' ' . $b->kendaraan->tipe : $b->kendaraan->merk . ' ' . substr($b->kendaraan->tipe, 0, 2) . '***',
                'plat_nomor' => $isOwn ? $b->kendaraan->plat_nomor : $maskedPlat,
                'paket' => $b->paket_servis->count() > 0 ? $b->paket_servis->pluck('nama_paket')->implode(', ') : 'Servis Umum',
                'status' => $b->status,
            ];
        });

        // Load daily schedules to display availability status in calendar cells
        $schedules = JadwalHarian::orderBy('tanggal', 'asc')->get();
        $schedulesJson = $schedules->mapWithKeys(function($s) {
            $sisa = $s->kapasitas_menit - $s->terpakai_menit;
            return [
                $s->tanggal => [
                    'id' => $s->id,
                    'kapasitas_menit' => $s->kapasitas_menit,
                    'terpakai_menit' => $s->terpakai_menit,
                    'sisa_kuota' => max(0, $sisa),
                    'is_holiday' => $s->kapasitas_menit == 0,
                    'is_full' => $sisa <= 0 && $s->kapasitas_menit > 0,
                    'jam_buka' => substr($s->jam_buka, 0, 5),
                    'jam_tutup' => substr($s->jam_tutup, 0, 5),
                ]
            ];
        });

        return view('user.calendar.index', compact('bookingsJson', 'schedulesJson'));
    }

    // Ini cuma buat nyediain data paket servis ke JavaScript pas user pilih paket di modal booking (format JSON)
    public function getPackages()
    {
        $packages = PaketServis::orderBy('harga_jasa', 'asc')->get();
        return response()->json($packages);
    }
}
