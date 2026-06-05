<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; margin: 0; padding: 0;">
    <table>
        <!-- Define column widths explicitly for MS Excel & Google Sheets -->
        <colgroup>
            <col width="220" style="width: 220px;" /> <!-- Col 1: Bulan/Rentang/No -->
            <col width="140" style="width: 140px;" /> <!-- Col 2: Total Booking / Invoice -->
            <col width="120" style="width: 120px;" /> <!-- Col 3: Selesai / Tanggal -->
            <col width="180" style="width: 180px;" /> <!-- Col 4: Pending / Pelanggan -->
            <col width="140" style="width: 140px;" /> <!-- Col 5: Aktif / No. Polisi -->
            <col width="160" style="width: 160px;" /> <!-- Col 6: Batal / Sepeda Motor -->
            <col width="280" style="width: 280px;" /> <!-- Col 7: Pendapatan / Paket Servis -->
            <col width="140" style="width: 140px;" /> <!-- Col 8: Status Booking -->
            <col width="140" style="width: 140px;" /> <!-- Col 9: Status Bayar -->
            <col width="160" style="width: 160px;" /> <!-- Col 10: Total Biaya -->
        </colgroup>

        <!-- Main Title Header -->
        <tr height="70">
            <td colspan="10" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 18pt; font-weight: bold; text-align: center; color: #0f172a; vertical-align: middle; padding: 20px 0;">
                LAPORAN REKAPITULASI &amp; TRANSAKSI DETAIL BENGKEL
            </td>
        </tr>
        <tr height="40">
            <td colspan="10" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 11pt; text-align: center; color: #475569; vertical-align: middle; padding-bottom: 25px;">
                Bengkel MotoSkensa &bull; Tanggal Ekspor: {{ date('d F Y, H:i') }}
            </td>
        </tr>
        <tr height="30">
            <td colspan="10" style="border: none;"></td>
        </tr>

        <!-- Section 1: Ringkasan Bulanan -->
        <tr height="55">
            <td colspan="10" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 12.5pt; font-weight: bold; background-color: #cbd5e1; color: #0f172a; padding: 14px 16px; border: 1px solid #94a3b8; text-align: left; vertical-align: middle;">
                I. RINGKASAN REKAPITULASI BULANAN
            </td>
        </tr>
        <thead>
            <tr height="50">
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Bulan &amp; Tahun</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Total Booking</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Selesai</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Pending</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Aktif (Approved/Progress)</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Batal / Ditolak</th>
                <th colspan="4" style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: right; vertical-align: middle;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totBookingM = 0;
                $totSelesaiM = 0;
                $totPendingM = 0;
                $totAktifM = 0;
                $totBatalM = 0;
                $totRevenueM = 0;
            @endphp
            @foreach($recapData as $row)
                @php
                    $totBookingM += $row['total_bookings'];
                    $totSelesaiM += $row['completed_bookings'];
                    $totPendingM += $row['pending_bookings'];
                    $totAktifM += $row['active_bookings'];
                    $totBatalM += $row['cancelled_bookings'];
                    $totRevenueM += $row['revenue'];
                @endphp
                <tr height="45">
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: left; font-weight: bold; color: #0f172a; vertical-align: middle;">
                        {{ $row['month_label'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #1e293b; vertical-align: middle;">
                        {{ $row['total_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #059669; font-weight: bold; vertical-align: middle;">
                        {{ $row['completed_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #ea580c; font-weight: bold; vertical-align: middle;">
                        {{ $row['pending_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #2563eb; font-weight: bold; vertical-align: middle;">
                        {{ $row['active_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #dc2626; font-weight: bold; vertical-align: middle;">
                        {{ $row['cancelled_bookings'] }}
                    </td>
                    <td colspan="4" style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: right; color: #059669; font-weight: bold; vertical-align: middle;">
                        Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr height="48" style="background-color: #f1f5f9; font-weight: bold;">
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: left; color: #0f172a; font-weight: bold; vertical-align: middle;">
                    TOTAL KESELURUHAN BULANAN
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #0f172a; vertical-align: middle;">
                    {{ $totBookingM }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #059669; font-weight: bold; vertical-align: middle;">
                    {{ $totSelesaiM }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #ea580c; font-weight: bold; vertical-align: middle;">
                    {{ $totPendingM }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #2563eb; font-weight: bold; vertical-align: middle;">
                    {{ $totAktifM }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #dc2626; font-weight: bold; vertical-align: middle;">
                    {{ $totBatalM }}
                </td>
                <td colspan="4" style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: right; color: #059669; font-weight: bold; vertical-align: middle;">
                    Rp {{ number_format($totRevenueM, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>

        <!-- Spacer Row -->
        <tr>
            <td colspan="10" style="height: 35px; border: none;"></td>
        </tr>

        <!-- Section 2: Ringkasan Mingguan -->
        <tr height="55">
            <td colspan="10" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 12.5pt; font-weight: bold; background-color: #cbd5e1; color: #0f172a; padding: 14px 10px; border: 1px solid #94a3b8; text-align: left; vertical-align: middle;">
                II. RINGKASAN REKAPITULASI MINGGUAN
            </td>
        </tr>
        <thead>
            <tr height="50">
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Rentang Tanggal (Mingguan)</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Total Booking</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Selesai</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Pending</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Aktif (Approved/Progress)</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Batal / Ditolak</th>
                <th colspan="4" style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: right; vertical-align: middle;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totBookingW = 0;
                $totSelesaiW = 0;
                $totPendingW = 0;
                $totAktifW = 0;
                $totBatalW = 0;
                $totRevenueW = 0;
            @endphp
            @foreach($weeklyRecapData as $row)
                @php
                    $totBookingW += $row['total_bookings'];
                    $totSelesaiW += $row['completed_bookings'];
                    $totPendingW += $row['pending_bookings'];
                    $totAktifW += $row['active_bookings'];
                    $totBatalW += $row['cancelled_bookings'];
                    $totRevenueW += $row['revenue'];
                @endphp
                <tr height="45">
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: left; font-weight: bold; color: #0f172a; vertical-align: middle;">
                        {{ $row['week_label'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #1e293b; vertical-align: middle;">
                        {{ $row['total_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #059669; font-weight: bold; vertical-align: middle;">
                        {{ $row['completed_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #ea580c; font-weight: bold; vertical-align: middle;">
                        {{ $row['pending_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #2563eb; font-weight: bold; vertical-align: middle;">
                        {{ $row['active_bookings'] }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #dc2626; font-weight: bold; vertical-align: middle;">
                        {{ $row['cancelled_bookings'] }}
                    </td>
                    <td colspan="4" style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: right; color: #059669; font-weight: bold; vertical-align: middle;">
                        Rp {{ number_format($row['revenue'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr height="48" style="background-color: #f1f5f9; font-weight: bold;">
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: left; color: #0f172a; font-weight: bold; vertical-align: middle;">
                    TOTAL KESELURUHAN MINGGUAN
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #0f172a; vertical-align: middle;">
                    {{ $totBookingW }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #059669; font-weight: bold; vertical-align: middle;">
                    {{ $totSelesaiW }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #ea580c; font-weight: bold; vertical-align: middle;">
                    {{ $totPendingW }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #2563eb; font-weight: bold; vertical-align: middle;">
                    {{ $totAktifW }}
                </td>
                <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: center; color: #dc2626; font-weight: bold; vertical-align: middle;">
                    {{ $totBatalW }}
                </td>
                <td colspan="4" style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 16px 12px; text-align: right; color: #059669; font-weight: bold; vertical-align: middle;">
                    Rp {{ number_format($totRevenueW, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>

        <!-- Spacer Row -->
        <tr>
            <td colspan="10" style="height: 35px; border: none;"></td>
        </tr>

        <!-- Section 3: Rincian Transaksi Detail -->
        <tr height="55">
            <td colspan="10" style="font-family: 'Segoe UI', Arial, sans-serif; font-size: 12.5pt; font-weight: bold; background-color: #cbd5e1; color: #0f172a; padding: 14px 10px; border: 1px solid #94a3b8; text-align: left; vertical-align: middle;">
                III. RINCIAN TRANSAKSI DETAIL
            </td>
        </tr>
        <thead>
            <tr height="50">
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle; width: 40px;">No</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">No. Invoice</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Tanggal</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Pelanggan</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">No. Polisi</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Sepeda Motor</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle; width: 280px;">Paket Servis</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Status Booking</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: center; vertical-align: middle;">Status Bayar</th>
                <th style="border: 1px solid #94a3b8; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10.5pt; padding: 16px 12px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: right; vertical-align: middle;">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailedBookings as $index => $booking)
                @php
                    $statusColor = '#ea580c'; // default orange
                    if ($booking->status === 'completed') {
                        $statusColor = '#059669'; // green
                    } elseif (in_array($booking->status, ['approved', 'in_progress'])) {
                        $statusColor = '#2563eb'; // blue
                    } elseif (in_array($booking->status, ['cancelled', 'rejected'])) {
                        $statusColor = '#dc2626'; // red
                    }

                    $payColor = ($booking->payment_status === 'paid') ? '#059669' : '#dc2626';
                    $motorText = ($booking->kendaraan->merk ?? '') . ' ' . ($booking->kendaraan->tipe ?? '');
                    $packagesText = $booking->paket_servis->pluck('nama_paket')->join(', ') ?: 'Servis Mandiri / Kustom';
                @endphp
                <tr height="45">
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #475569; vertical-align: middle;">
                        {{ $index + 1 }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #0f172a; font-weight: bold; vertical-align: middle;">
                        {{ $booking->nomor_invoice ?? '-' }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #475569; vertical-align: middle;">
                        {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: left; color: #0f172a; vertical-align: middle;">
                        {{ $booking->user->name ?? '-' }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: #0f172a; font-weight: bold; vertical-align: middle;">
                        {{ $booking->kendaraan->plat_nomor ?? '-' }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: left; color: #475569; vertical-align: middle;">
                        {{ $motorText }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: left; color: #475569; vertical-align: middle;">
                        {{ $packagesText }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: {{ $statusColor }}; font-weight: bold; vertical-align: middle;">
                        {{ ucfirst($booking->status) }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: center; color: {{ $payColor }}; font-weight: bold; vertical-align: middle;">
                        {{ ucfirst($booking->payment_status ?? 'unpaid') }}
                    </td>
                    <td style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 14px 12px; text-align: right; color: #0f172a; font-weight: bold; vertical-align: middle;">
                        Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr height="60">
                    <td colspan="10" style="border: 1px solid #cbd5e1; font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; padding: 20px; text-align: center; color: #64748b; vertical-align: middle;">
                        Belum ada riwayat transaksi detail.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
