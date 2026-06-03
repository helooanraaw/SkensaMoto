<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .title {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
        }
        .subtitle {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11pt;
            color: #64748b;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border: 1px solid #cbd5e1;
        }
        td {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 8px;
            border: 1px solid #cbd5e1;
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .revenue-cell {
            color: #059669;
            font-weight: bold;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="7" class="title">LAPORAN REKAPITULASI BULANAN</td>
        </tr>
        <tr>
            <td colspan="7" class="subtitle">Bengkel MotoSkensa - Tanggal Ekspor: {{ date('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <thead>
            <tr>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Bulan & Tahun</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Total Booking</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Selesai</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Pending</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Aktif (Approved/Progress)</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Batal / Ditolak</th>
                <th style="background-color: #1e293b; color: #ffffff; font-weight: bold; border: 1px solid #cbd5e1;">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totBooking = 0;
                $totSelesai = 0;
                $totPending = 0;
                $totAktif = 0;
                $totBatal = 0;
                $totRevenue = 0;
            @endphp
            @foreach($recapData as $row)
                @php
                    $totBooking += $row['total_bookings'];
                    $totSelesai += $row['completed_bookings'];
                    $totPending += $row['pending_bookings'];
                    $totAktif += $row['active_bookings'];
                    $totBatal += $row['cancelled_bookings'];
                    $totRevenue += $row['revenue'];
                @endphp
                <tr>
                    <td class="text-left font-bold" style="border: 1px solid #cbd5e1;">{{ $row['month_label'] }}</td>
                    <td style="border: 1px solid #cbd5e1;">{{ $row['total_bookings'] }}</td>
                    <td style="color: #059669; font-weight: bold; border: 1px solid #cbd5e1;">{{ $row['completed_bookings'] }}</td>
                    <td style="color: #ea580c; border: 1px solid #cbd5e1;">{{ $row['pending_bookings'] }}</td>
                    <td style="color: #2563eb; border: 1px solid #cbd5e1;">{{ $row['active_bookings'] }}</td>
                    <td style="color: #dc2626; border: 1px solid #cbd5e1;">{{ $row['cancelled_bookings'] }}</td>
                    <td class="text-right revenue-cell" style="border: 1px solid #cbd5e1;">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row" style="background-color: #f8fafc; font-weight: bold;">
                <td class="text-left" style="border: 1px solid #cbd5e1;">TOTAL KESELURUHAN</td>
                <td style="border: 1px solid #cbd5e1;">{{ $totBooking }}</td>
                <td style="color: #059669; border: 1px solid #cbd5e1;">{{ $totSelesai }}</td>
                <td style="color: #ea580c; border: 1px solid #cbd5e1;">{{ $totPending }}</td>
                <td style="color: #2563eb; border: 1px solid #cbd5e1;">{{ $totAktif }}</td>
                <td style="color: #dc2626; border: 1px solid #cbd5e1;">{{ $totBatal }}</td>
                <td class="text-right" style="color: #059669; font-weight: bold; border: 1px solid #cbd5e1;">Rp {{ number_format($totRevenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
