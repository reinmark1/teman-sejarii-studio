@extends('layouts.app')

@section('title', 'Laporan Penjualan — teman sejarii')

@section('content')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-taupe mb-1">Rekap Keuangan Studio</p>
            <h1 class="font-editorial text-3xl sm:text-4xl font-semibold">Laporan Penjualan &amp; Stok</h1>
        </div>

        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="label-editorial">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-editorial !bg-white !py-1.5 !text-xs">
            </div>
            <div>
                <label class="label-editorial">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-editorial !bg-white !py-1.5 !text-xs">
            </div>
            <button type="submit" class="btn-espresso !py-2 !text-xs">Terapkan</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card-editorial p-5">
            <span class="label-editorial">Total Omzet</span>
            <p class="text-2xl font-semibold tabular-nums">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>

        <div class="card-editorial p-5">
            <span class="label-editorial">Laba Bersih</span>
            <p class="text-2xl font-semibold text-blush-dark tabular-nums">Rp {{ number_format($totalProfit, 0, ',', '.') }}</p>
        </div>

        <div class="card-editorial p-5">
            <span class="label-editorial">Produk Terjual</span>
            <p class="text-2xl font-semibold tabular-nums">{{ $totalItemsSold }} <span class="text-xs font-normal text-taupe">unit</span></p>
        </div>

        <div class="card-editorial p-5">
            <span class="label-editorial">Belanja Restock</span>
            <p class="text-2xl font-semibold text-taupe tabular-nums">Rp {{ number_format($totalRestockCost, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="card-editorial overflow-hidden">
        <div class="px-6 py-4 border-b border-sand flex items-center justify-between">
            <h2 class="font-editorial text-2xl">Buku Catatan Transaksi</h2>
            <span class="text-xs text-taupe">{{ $logs->count() }} aktivitas tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ivory border-b border-sand text-xs uppercase tracking-wider text-taupe">
                    <tr>
                        <th class="px-6 py-3 font-medium">Tanggal</th>
                        <th class="px-6 py-3 font-medium">Produk</th>
                        <th class="px-6 py-3 font-medium">Aktivitas</th>
                        <th class="px-6 py-3 font-medium text-right">Qty</th>
                        <th class="px-6 py-3 font-medium text-right">Nominal</th>
                        <th class="px-6 py-3 font-medium text-right">Keuntungan</th>
                        <th class="px-6 py-3 font-medium">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand">
                    @forelse($logs as $log)
                        <tr class="hover:bg-ivory/60 transition">
                            <td class="px-6 py-3.5 text-xs text-taupe tabular-nums whitespace-nowrap">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-3.5 font-medium">
                                @if($log->product)
                                    <a href="{{ route('products.show', $log->product) }}" class="hover-text-blush transition">
                                        {{ $log->product->name }}
                                    </a>
                                @else
                                    <span class="text-taupe italic">Produk dihapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                @if($log->type === 'out')
                                    <span class="badge-blush">Penjualan</span>
                                @elseif($log->type === 'in')
                                    <span class="px-2 py-0.5 text-xs rounded bg-emerald-50 text-emerald-800 border border-emerald-200">Stok Masuk</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded badge-stock-low">Koreksi</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right tabular-nums">{{ $log->quantity }}</td>
                            <td class="px-6 py-3.5 text-right tabular-nums">
                                {{ $log->total_price > 0 ? 'Rp ' . number_format($log->total_price, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right tabular-nums font-medium text-blush-dark">
                                {{ $log->type === 'out' ? 'Rp ' . number_format($log->profit, 0, ',', '.') : '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-xs text-taupe">{{ $log->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center font-editorial italic text-lg text-taupe">
                                Tidak ada transaksi pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection