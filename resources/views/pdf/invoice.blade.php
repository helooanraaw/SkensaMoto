<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $booking->nomor_invoice }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700;900&display=swap');
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 12px;
            margin: 0;
            padding: 20px 30px;
        }
        .header {
            border-bottom: 3px solid #dc2626;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
        }
        .header td {
            vertical-align: top;
        }
        .logo-text {
            font-family: 'Urbanist', sans-serif;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: -1px;
            line-height: 1;
        }
        .text-red { color: #dc2626; }
        .text-blue { color: #172554; }
        
        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 10px 10px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .total-row td {
            font-weight: bold;
            font-size: 13px;
            padding: 8px 10px;
            background-color: #ffffff;
        }
        .grand-total td {
            font-weight: bold;
            font-size: 18px;
            background-color: #f1f5f9;
            color: #172554;
            border-top: 2px solid #172554;
            padding: 10px 10px;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="vertical-align: middle; white-space: nowrap;">
                    <img src="{{ public_path('images/MotoSkensaLogo1.png') }}" alt="Logo" style="width: 50px; height: auto; vertical-align: middle; display: inline-block;">
                    <span class="logo-text" style="font-size: 32px; line-height: 1; vertical-align: middle; display: inline-block; margin-left: 0px;">
                        @if($appSetting && $appSetting->nama_bengkel)
                            {{ $appSetting->nama_bengkel }}
                        @else
                            <span class="text-red">Moto</span><span class="text-blue">Skensa</span>
                        @endif
                    </span>
                    <div style="margin-top: 5px; color: #64748b; font-size: 11px;">
                        {{ $appSetting->alamat ?? 'Jl. HOS. Cokroaminoto No. 84 Denpasar, Bali 80118' }}<br>
                        Telp: {{ $appSetting->telepon ?? '081236396662' }}
                    </div>
                </td>
                <td class="text-right">
                    <h2 style="margin: 0; color: #1e293b; font-size: 28px;">INVOICE</h2>
                    <p style="margin: 5px 0 0 0; font-weight: bold; color: #dc2626;">#{{ $booking->nomor_invoice }}</p>
                    <p style="margin: 5px 0 0 0; color: #64748b;">Tanggal: {{ \Carbon\Carbon::parse($booking->tanggal)->format('d F Y') }}</p>
                    <p style="margin: 5px 0 0 0; font-weight: bold; color: {{ ($booking->payment_status ?? 'unpaid') === 'paid' ? '#10b981' : '#dc2626' }};">
                        STATUS: {{ ($booking->payment_status ?? 'unpaid') === 'paid' ? 'LUNAS' : 'BELUM BAYAR' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <div style="font-weight: bold; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">Data Pelanggan</div>
                <table style="width: 100%">
                    <tr>
                        <td class="info-label">Nama</td>
                        <td>: {{ $booking->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">No. HP</td>
                        <td>: {{ $booking->user->nomor_telepon ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <div style="font-weight: bold; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">Data Kendaraan</div>
                <table style="width: 100%">
                    <tr>
                        <td class="info-label">Kendaraan</td>
                        <td>: {{ $booking->kendaraan->merk }} {{ $booking->kendaraan->tipe }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Plat Nomor</td>
                        <td>: {{ $booking->kendaraan->plat_nomor }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-bottom: 15px;">
        <strong>Keluhan:</strong> {{ $booking->keluhan ?: '-' }}
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="45%">Deskripsi Pekerjaan / Sparepart</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="15%" class="text-right">Harga Satuan</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1; 
                $totalJasa = 0;
                $totalSparepart = 0;
            @endphp
            
            <!-- Jasa / Paket Servis -->
            @foreach($booking->paket_servis as $paket)
                @php $totalJasa += $paket->harga_jasa; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        <strong>[Paket Servis]</strong> {{ $paket->nama_paket }}<br>
                        <span style="font-size: 10px; color: #64748b;">{{ $paket->deskripsi }}</span>
                    </td>
                    <td class="text-center">1</td>
                    <td class="text-right">Rp {{ number_format($paket->harga_jasa, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($paket->harga_jasa, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Estimasi Tambahan -->
            @if($booking->biaya_jasa > 0)
                @php $totalJasa += $booking->biaya_jasa; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>[Jasa Tambahan]</strong> Estimasi Mekanik</td>
                    <td class="text-center">1</td>
                    <td class="text-right">Rp {{ number_format($booking->biaya_jasa, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($booking->biaya_jasa, 0, ',', '.') }}</td>
                </tr>
            @endif

            <!-- Spareparts -->
            @foreach($booking->pemakaian_barang as $barang)
                @if($barang->pivot->disetujui)
                    @php 
                        $subtotal = $barang->pivot->jumlah * $barang->harga_satuan;
                        $totalSparepart += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td><strong>[Sparepart]</strong> {{ $barang->nama_barang }}</td>
                        <td class="text-center">{{ $barang->pivot->jumlah }} {{ $barang->satuan }}</td>
                        <td class="text-right">Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endif
            @endforeach

            <!-- Totals -->
            <tr class="total-row">
                <td colspan="4" class="text-right">Subtotal Jasa:</td>
                <td class="text-right">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="4" class="text-right">Subtotal Sparepart:</td>
                <td class="text-right">Rp {{ number_format($totalSparepart, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td colspan="4" class="text-right">TOTAL PEMBAYARAN:</td>
                <td class="text-right">Rp {{ number_format($totalJasa + $totalSparepart, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }} oleh Sistem {{ $appSetting->nama_bengkel ?? 'MotoSkensa' }}<br>
        Terima kasih atas kepercayaan Anda menyervis kendaraan di bengkel kami.
    </div>

</body>
</html>
