<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} - LELE Pak Dedi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-light py-4">
<div class="container">
    <div class="d-flex justify-content-end mb-3 no-print gap-2">
        <button onclick="window.print()" class="btn btn-success"><i class="bi bi-printer me-1"></i> Cetak Invoice</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
    </div>

    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white">
        <div class="row border-bottom pb-4 mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold text-success mb-1">LELE Pak Dedi</h3>
                <div class="small text-muted">{{ \App\Models\Setting::getValue('store_address', 'Jl. Raya Tambun Selatan No. 88, Bekasi') }}</div>
                <div class="small text-muted">Telp/WA: {{ \App\Models\Setting::getValue('store_phone', '0812-3456-7890') }}</div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <h4 class="fw-bold mb-1">INVOICE</h4>
                <div class="fw-semibold">{{ $order->order_number }}</div>
                <div class="small text-muted">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</div>
                <span class="badge bg-success mt-1">{{ $order->status_label }}</span>
            </div>
        </div>

        <div class="row mb-4 small">
            <div class="col-md-6">
                <div class="text-muted fw-semibold mb-1">Kepada Pelanggan:</div>
                <div class="fw-bold">{{ $order->shipping_recipient }}</div>
                <div>{{ $order->shipping_phone }}</div>
                <div>{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <div class="text-muted fw-semibold mb-1">Metode Pembayaran &amp; Pengiriman:</div>
                <div>Pembayaran: <strong>{{ $order->payment?->method_label }}</strong></div>
                <div>Pengiriman: <strong>{{ $order->shipping?->method_label }}</strong></div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->product_name }}</td>
                            <td>{{ $item->fish_size ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }} / {{ $item->unit }}</td>
                            <td class="text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end">Subtotal</td>
                        <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end">Ongkos Kirim</td>
                        <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end fw-bold fs-5">Total</td>
                        <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center small text-muted pt-3 border-top">
            Terima kasih telah berbelanja di <strong>LELE Pak Dedi</strong>. Kesegaran &amp; kualitas ikan lele adalah prioritas kami.
        </div>
    </div>
</div>
</body>
</html>
