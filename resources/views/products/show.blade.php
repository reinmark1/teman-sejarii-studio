@extends('layouts.app')

@section('title', $product->name . ' — teman sejarii')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-taupe">Detail Koleksi</p>
        </div>
        <a href="{{ route('products.edit', $product) }}" class="btn-outline-sand !text-xs">
            Edit Koleksi
        </a>
    </div>

    <div class="card-editorial p-6 sm:p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <div class="aspect-[4/5] bg-ivory rounded-md overflow-hidden border border-sand">
                @if($product->image)
                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center font-editorial italic text-xl text-taupe/50">
                        teman sejarii
                    </div>
                @endif
            </div>

            <div class="md:col-span-2 space-y-6">
                <div>
                    <!-- Tulisan "Detail Koleksi" yang lama di sini sudah dihapus -->
                    <h1 class="font-editorial text-3xl sm:text-4xl font-semibold">{{ $product->name }}</h1>
                    @if($product->description)
                        <p class="text-sm text-taupe mt-2 leading-relaxed">{{ $product->description }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-sand">
                    <div>
                        <span class="label-editorial">Harga Jual</span>
                        <p class="text-lg font-semibold tabular-nums">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="label-editorial">Harga Modal</span>
                        <p class="text-lg font-semibold text-taupe tabular-nums">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="label-editorial">Sisa Stok</span>
                        <p class="text-lg font-semibold tabular-nums">{{ $product->stock }} unit</p>
                    </div>
                    <div>
                        <span class="label-editorial">Total Terjual</span>
                        <p class="text-lg font-semibold tabular-nums">{{ $totalSold }} unit</p>
                    </div>
                    <div>
                        <span class="label-editorial">Total Omzet</span>
                        <p class="text-lg font-semibold tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="label-editorial">Total Laba Bersih</span>
                        <p class="text-lg font-semibold text-blush-dark tabular-nums">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-editorial p-6 sm:p-8 mb-8">
        <div class="mb-4">
            <h2 class="font-editorial text-2xl">Performa Penjualan Produk</h2>
            <p class="text-xs text-taupe">Perbandingan jumlah unit terjual dan keuntungan per transaksi</p>
        </div>
        <div class="w-full">
            <canvas id="transactionChart" height="105"
                    data-labels='@json($transactionLabels)'
                    data-quantities='@json($transactionQuantities)'
                    data-profits='@json($transactionProfits)'>
            </canvas>
        </div>
    </div>

    <div class="card-editorial overflow-hidden">
        <div class="px-6 py-4 border-b border-sand">
            <h2 class="font-editorial text-2xl">Riwayat Pergerakan Stok Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ivory border-b border-sand text-xs uppercase tracking-wider text-taupe">
                    <tr>
                        <th class="px-6 py-3 font-medium">Waktu</th>
                        <th class="px-6 py-3 font-medium">Jenis</th>
                        <th class="px-6 py-3 font-medium text-right">Jumlah</th>
                        <th class="px-6 py-3 font-medium text-right">Laba</th>
                        <th class="px-6 py-3 font-medium">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand">
                    @forelse($allLogs as $log)
                        <tr>
                            <td class="px-6 py-3.5 text-xs text-taupe tabular-nums">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-3.5">
                                @if($log->type === 'out')
                                    <span class="badge-blush">Terjual</span>
                                @elseif($log->type === 'in')
                                    <span class="px-2 py-0.5 text-xs rounded bg-emerald-50 text-emerald-800 border border-emerald-200">Stok Masuk</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded badge-stock-low">Koreksi</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right font-medium tabular-nums">
                                {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                            </td>
                            <td class="px-6 py-3.5 text-right tabular-nums text-blush-dark">
                                {{ $log->type === 'out' ? 'Rp ' . number_format($log->profit, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-xs text-taupe">{{ $log->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-xs text-taupe">Belum ada riwayat transaksi untuk produk ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/product-chart.js') }}"></script>
@endpush