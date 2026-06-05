<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Kendaraan;
use App\Models\JadwalHarian;
use App\Models\Inventory;
use App\Models\PaketServis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_cannot_perform_write_actions_but_can_view_recap()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@skensa.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567890',
            'role' => 'superadmin'
        ]);

        $motor = Kendaraan::create([
            'user_id' => $superadmin->id,
            'plat_nomor' => 'DK 1111 XX',
            'merk' => 'Honda',
            'tipe' => 'Beat',
            'tahun' => 2021
        ]);

        $booking = Booking::create([
            'user_id' => $superadmin->id,
            'kendaraan_id' => $motor->id,
            'tanggal' => '2026-06-10',
            'status' => 'pending',
            'keluhan' => 'mesin brebet'
        ]);

        // Attempting to approve booking as superadmin should be blocked
        $response = $this->actingAs($superadmin)
            ->patch(route('admin.bookings.approve', $booking->id), [
                'estimasi_total_menit' => 60
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Superadmin hanya memiliki akses lihat (read-only) untuk fitur ini.');

        // Attempting to create inventory item should be blocked
        $response = $this->actingAs($superadmin)
            ->post(route('admin.inventory.store'), [
                'nama_barang' => 'Ban Corsa R46',
                'satuan' => 'Pcs',
                'stok' => 10,
                'harga_satuan' => 350000
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Superadmin hanya memiliki akses lihat (read-only) untuk fitur ini.');

        // Superadmin should be able to view recap
        $response = $this->actingAs($superadmin)
            ->get(route('admin.recap.index'));

        $response->assertStatus(200);

        // Superadmin should be able to download Excel
        $response = $this->actingAs($superadmin)
            ->get(route('admin.recap.excel'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="rekap_pendapatan_booking_' . date('Ymd') . '.xls"');
    }

    public function test_admin_can_perform_write_actions_but_cannot_view_recap()
    {
        $admin = User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@skensa.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567890',
            'role' => 'admin'
        ]);

        // Admins should be blocked from viewing recap (redirected to dashboard)
        $response = $this->actingAs($admin)
            ->get(route('admin.recap.index'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_calendar_privacy_masking()
    {
        $userA = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567891',
            'role' => 'user'
        ]);

        $userB = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567892',
            'role' => 'user'
        ]);

        $motorA = Kendaraan::create([
            'user_id' => $userA->id,
            'plat_nomor' => 'DK 1234 AB',
            'merk' => 'Honda',
            'tipe' => 'Vario 150',
            'tahun' => 2020
        ]);

        $bookingA = Booking::create([
            'user_id' => $userA->id,
            'kendaraan_id' => $motorA->id,
            'tanggal' => '2026-06-15',
            'status' => 'pending',
            'keluhan' => 'ganti oli'
        ]);

        // User A views the calendar
        $response = $this->actingAs($userA)
            ->get(route('user.calendar.index'));

        $response->assertStatus(200);
        // User A should see their own details unmasked
        $response->assertSee('John Doe');
        $response->assertSee('DK 1234 AB');

        // User B views the calendar
        $response = $this->actingAs($userB)
            ->get(route('user.calendar.index'));

        $response->assertStatus(200);
        // User B should NOT see User A's real name and plate number
        $response->assertDontSee('John Doe');
        $response->assertDontSee('DK 1234 AB');
        // User B should see masked versions
        $response->assertSee('John D***');
        $response->assertSee('DK *** AB');
    }

    public function test_admin_can_send_quotation_and_bypass_customer_confirmation()
    {
        $admin = User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin2@skensa.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567890',
            'role' => 'admin'
        ]);

        $user = User::create([
            'name' => 'Pelanggan',
            'email' => 'pelanggan@skensa.com',
            'password' => bcrypt('password'),
            'nomor_telepon' => '081234567899',
            'role' => 'user'
        ]);

        $motor = Kendaraan::create([
            'user_id' => $user->id,
            'plat_nomor' => 'DK 2222 YY',
            'merk' => 'Honda',
            'tipe' => 'Vario',
            'tahun' => 2022
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'kendaraan_id' => $motor->id,
            'tanggal' => '2026-06-12',
            'status' => 'in_progress',
            'keluhan' => 'mesin mati'
        ]);

        $item = Inventory::create([
            'nama_barang' => 'Oli Mesin MPX2',
            'satuan' => 'Botol',
            'stok' => 50,
            'harga_satuan' => 45000
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.bookings.send_quotation', $booking->id), [
                'catatan_kerusakan' => 'Perlu ganti oli mesin.',
                'barang_id' => [$item->id],
                'jumlah' => [1]
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('open_wa_url');

        // Check if database updated quotation status directly to approved
        $booking->refresh();
        $this->assertEquals('approved', $booking->quotation_status);

        // Check if spare part was saved as approved automatically
        $pemakaian = \DB::table('pemakaian_barang')->where('booking_id', $booking->id)->first();
        $this->assertNotNull($pemakaian);
        $this->assertEquals($item->id, $pemakaian->barang_id);
        $this->assertEquals(1, $pemakaian->jumlah);
        $this->assertTrue((bool)$pemakaian->is_approved);
    }
}

