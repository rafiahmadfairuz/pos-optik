<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nota Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: monospace;
            font-size: 11px;
            width: 58mm;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
        }

        .info,
        .totals {
            width: 100%;
        }

        .totals td {
            padding: 4px 0;
            vertical-align: top;
        }

        .line {
            border-bottom: 1px dashed #000;
            margin: 4px 0;
        }

        .bold {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        .icon {
            width: 12px;
            display: inline-block;
        }
    </style>
</head>

<body>

    {{-- Header Toko --}}
    <div class="header">
        <h2>{{ strtoupper($cabang->nama) }}</h2>
        <p>{{ $cabang->alamat }}</p>
    </div>

    {{-- Info Order --}}
    <div class="center">
        <p><i class="bi bi-receipt-cutoff"></i> Nota: #{{ $order->id }}</p>
        <p><i class="bi bi-calendar-event"></i> Tanggal: {{ $order->order_date }}</p>
    </div>

    <div class="line"></div>

    {{-- Total --}}
    <table class="totals">
        <tr>
            <td><i class="bi bi-cash-stack"></i> Total</td>
            <td style="text-align:right;">Rp {{ number_format($order->total, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><i class="bi bi-tag"></i> Diskon</td>
            <td style="text-align:right;">Rp {{ number_format($order->diskon, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><i class="bi bi-shield-check"></i> Asuransi</td>
            <td style="text-align:right;">Rp {{ number_format($order->asuransi?->nominal ?? 0, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    {{-- Total Final --}}
    <table class="totals">
        <tr>
            <td><i class="bi bi-calculator"></i> Total Final</td>
            <td style="text-align:right;" class="bold">Rp {{ number_format($order->perlu_dibayar, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><i class="bi bi-wallet2"></i> Dibayar</td>
            <td style="text-align:right;">Rp {{ number_format($order->customer_paying, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><i class="bi bi-dash-circle"></i> Kurang Bayar</td>
            <td style="text-align:right;">Rp {{ number_format($order->kurang_bayar, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td><i class="bi bi-arrow-repeat"></i> Kembalian</td>
            <td style="text-align:right;">Rp {{ number_format($order->kembalian ?? 0, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    {{-- Footer --}}
    <div class="footer">
        <p><i class="bi bi-patch-check-fill"></i> Terima kasih telah berbelanja</p>
        <p>Periksa kembali pesanan Anda sebelum meninggalkan toko.</p>
        <p>Semoga puas dengan layanan kami!</p>
        <p>~~ Sampai Jumpa Lagi ~~</p>
    </div>


</body>

</html>
