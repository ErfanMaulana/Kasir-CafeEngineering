<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - Engineering Cafe</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #041562;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 11px;
            color: #11468F;
            font-weight: bold;
            margin: 2px 0;
        }
        .brand-address {
            font-size: 9px;
            color: #64748b;
            margin: 0;
        }
        .divider {
            border-bottom: 2px solid #041562;
            margin-bottom: 12px;
        }
        .report-title-box {
            text-align: center;
            margin-bottom: 15px;
            padding: 8px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #041562;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }
        .report-meta {
            font-size: 10px;
            color: #475569;
            margin: 0;
        }
        /* Statistik Agregat Box */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-card {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            border-radius: 6px;
            width: 25%;
        }
        .stats-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin: 0 0 4px 0;
        }
        .stats-value {
            font-size: 13px;
            font-weight: bold;
            color: #041562;
            margin: 0;
        }
        .stats-sub {
            font-size: 8.5px;
            color: #94a3b8;
            margin: 2px 0 0 0;
        }
        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #041562;
            color: #ffffff;
            font-size: 9.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 6px;
            text-align: left;
            border: 1px solid #041562;
        }
        .data-table td {
            padding: 6px 6px;
            font-size: 9.5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
        }
        .badge-queue {
            background-color: #041562;
            color: #ffffff;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .badge-paid {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-unpaid {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .badge-dinein {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }
        .badge-takeaway {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
        }
        .badge-qris {
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ede9fe;
        }
        .badge-cash {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #dcfce7;
        }
        .item-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .item-list li {
            margin-bottom: 2px;
            line-height: 1.25;
        }
        .item-note {
            font-size: 8px;
            color: #b45309;
            font-style: italic;
            display: block;
        }
        /* Footer Tanda Tangan */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .footer-table td {
            vertical-align: top;
            font-size: 10px;
        }
        .signature-box {
            text-align: center;
            width: 220px;
        }
        .signature-space {
            height: 50px;
        }
        .system-note {
            font-size: 8.5px;
            color: #94a3b8;
            font-style: italic;
            margin-top: 15px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    {{-- KOP LAPORAN RESMI --}}
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <h1 class="brand-title">Engineering Cafe</h1>
                <p class="brand-sub">Fakultas Teknik · Universitas Siliwangi (FT Mugarsari)</p>
                <p class="brand-address">Gedung Fakultas Teknik Universitas Siliwangi, Jl. Mugarsari, Kec. Tamansari, Kota Tasikmalaya, Jawa Barat 46196</p>
            </td>
            <td style="width: 30%; text-align: right;">
                <p style="margin: 0; font-size: 9px; color: #64748b;">KODE DOKUMEN: <strong>EC-REP-{{ strtoupper($period) }}</strong></p>
                <p style="margin: 2px 0 0 0; font-size: 9px; color: #64748b;">DICETAK: <strong>{{ now()->translatedFormat('d M Y, H:i') }} WIB</strong></p>
                
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- JUDUL DAN PERIODE --}}
    <div class="report-title-box">
        <h2 class="report-title">Laporan Riwayat Transaksi &amp; Penjualan</h2>
        <p class="report-meta">
            Periode: <strong>{{ $periodLabel }}</strong> 
            ({{ $startDate->translatedFormat('d M Y') }} s/d {{ $endDate->translatedFormat('d M Y') }})
            @if(!empty($search))
                · Filter Pencarian: <em>"{{ $search }}"</em>
            @endif
        </p>
    </div>

    {{-- KARTU STATISTIK AGREGAT --}}
    <table class="stats-table">
        <tr>
            <td class="stats-card" style="margin-right: 5px;">
                <p class="stats-label">Total Omzet</p>
                <p class="stats-value" style="color: #041562;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="stats-sub">Pendapatan Bersih</p>
            </td>
            <td class="stats-card">
                <p class="stats-label">Total Pesanan</p>
                <p class="stats-value" style="color: #11468F;">{{ $totalOrders }}</p>
                <p class="stats-sub">Transaksi Selesai</p>
            </td>
            <td class="stats-card">
                <p class="stats-label">Rata-rata Pesanan (AOV)</p>
                <p class="stats-value" style="color: #0d9488;">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                <p class="stats-sub">Nilai Rata-rata / Tiket</p>
            </td>
            <td class="stats-card">
                <p class="stats-label">Metode Bayar Dominan</p>
                <p class="stats-value" style="color: #7c3aed;">{{ $topPaymentMethod }}</p>
                <p class="stats-sub">QRIS: {{ $qrisPct }}% · Cash: {{ $cashPct }}%</p>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA TRANSAKSI --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 7%; text-align: center;">Antrean</th>
                <th style="width: 14%;">Tanggal &amp; Waktu</th>
                <th style="width: 13%;">Pelanggan</th>
                <th style="width: 11%;">Tipe</th>
                <th style="width: 27%;">Rincian Menu &amp; Catatan</th>
                <th style="width: 9%; text-align: center;">Metode</th>
                <th style="width: 9%; text-align: center;">Status</th>
                <th style="width: 10%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td style="text-align: center;">
                        <span class="badge-queue">{{ $order->queue_number }}</span>
                    </td>
                    <td>
                        <strong>{{ $order->created_at->translatedFormat('d M Y') }}</strong><br>
                        <span style="color: #64748b; font-size: 8.5px;">{{ $order->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong>
                    </td>
                    <td>
                        @if ($order->order_type === 'dine_in')
                            <span class="badge badge-dinein">Dine-in{{ $order->table_number ? ' (' . $order->table_number . ')' : '' }}</span>
                        @else
                            <span class="badge badge-takeaway">Takeaway</span>
                        @endif
                    </td>
                    <td>
                        <ul class="item-list">
                            @foreach ($order->items as $item)
                                <li>
                                    <strong>{{ $item->quantity }}x</strong> {{ $item->product->name ?? 'Produk Dihapus' }}
                                    <span style="color: #64748b;">(Rp {{ number_format($item->subtotal, 0, ',', '.') }})</span>
                                    @if (!empty($item->notes))
                                        <span class="item-note">Catatan: "{{ $item->notes }}"</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td style="text-align: center;">
                        @if (strtolower($order->payment_method ?? '') === 'qris')
                            <span class="badge badge-qris">QRIS</span>
                        @elseif (strtolower($order->payment_method ?? '') === 'cash')
                            <span class="badge badge-cash">Cash</span>
                        @else
                            <span style="color: #94a3b8;">—</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        @if ($order->payment_status === 'paid')
                            <span class="badge badge-paid">Lunas</span>
                        @else
                            <span class="badge badge-unpaid">Belum Bayar</span>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: bold; color: #041562;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #94a3b8;">
                        Tidak ada catatan riwayat transaksi pada filter periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($orders->count() > 0)
            <tfoot>
                <tr style="background-color: #f1f5f9; font-weight: bold;">
                    <td colspan="7" style="text-align: right; padding: 8px; text-transform: uppercase; font-size: 10px; color: #041562;">
                        Total Akumulasi Transaksi:
                    </td>
                    <td style="text-align: right; padding: 8px; font-size: 11px; color: #041562;">
                        Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    {{-- TANDA TANGAN & VALIDASI --}}
    <table class="footer-table">
        <tr>
            <td style="width: 60%;">
                <div style="padding-right: 20px;">
                    <p style="margin: 0; font-weight: bold; color: #041562;">Catatan &amp; Pengesahan:</p>
                    <p style="margin: 3px 0 0 0; color: #64748b; font-size: 9px; line-height: 1.4;">
                        Seluruh data pesanan di atas diekstraksi langsung dari basis data transaksi resmi Engineering Cafe Fakultas Teknik Universitas Siliwangi.
                    </p>
                </div>
            </td>
            <td style="width: 40%; text-align: right;">
                <div class="signature-box" style="float: right;">
                    <p style="margin: 0; color: #475569;">Tasikmalaya, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin: 2px 0 0 0; font-weight: bold; color: #041562;">Penanggung Jawab Kasir,</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; color: #041562; text-decoration: underline;">( ................................................. )</p>
                    <p style="margin: 2px 0 0 0; font-size: 8.5px; color: #64748b;">Engineering Cafe · FT Unsil</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="system-note">
        Dicetak otomatis oleh Sistem POS Online Engineering Cafe (FT · Mugarsari) · ID Transaksi Terverifikasi.
    </div>

</body>
</html>

