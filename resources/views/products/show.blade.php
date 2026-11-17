<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - {{ $product->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 30px;
            background: #f9f9f9;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .info p {
            margin: 5px 0;
        }
        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #007bff;
            text-decoration: none;
        }
        canvas {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Detail Produk: {{ $product->name }}</h1>

    <div class="info">
        <p><strong>Harga Jual:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        @if ($product->cost_price)
            <p><strong>Harga Modal:</strong> Rp{{ number_format($product->cost_price, 0, ',', '.') }}</p>
        @endif
        <p><strong>Stok Tersisa:</strong> {{ $product->stock }}</p>
        <p><strong>Total Terjual:</strong> {{ $totalSold }} unit</p>
        <p><strong>Total Pemasukan:</strong> Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p><strong>Total Keuntungan:</strong> Rp{{ number_format($totalProfit, 0, ',', '.') }}</p>
    </div>

    <h3>Grafik Per Transaksi Stok Keluar</h3>
    <canvas id="transactionChart" height="120"></canvas>

    <a href="{{ route('products.index') }}" class="back-link">← Kembali ke Daftar Produk</a>
</div>

<script>
    const ctx = document.getElementById('transactionChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($transactionLabels) !!},
            datasets: [
                {
                    label: 'Stok Keluar',
                    data: {!! json_encode($transactionQuantities) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'red',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Keuntungan (Rp)',
                    data: {!! json_encode($transactionProfits) !!},
                    type: 'line',
                    borderColor: 'green',
                    backgroundColor: 'rgba(0, 128, 0, 0.2)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: true },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                x: { title: { display: true, text: 'Waktu Transaksi' } },
                y: {
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: 'Stok Keluar' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    title: { display: true, text: 'Keuntungan (Rp)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
</script>

</body>
</html>
