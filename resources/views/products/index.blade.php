@extends('layouts.app')

@section('title', 'Katalog & Stok — teman sejarii')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-taupe mb-1">Inventaris &amp; Kasir Harian</p>
            <h1 class="font-editorial text-3xl sm:text-4xl font-semibold">Katalog Koleksi</h1>
        </div>

        <a href="{{ route('products.create') }}" class="btn-espresso">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card-editorial p-5">
            <span class="label-editorial !mb-1">Omzet Hari Ini</span>
            <p class="text-2xl font-semibold tabular-nums">
                Rp {{ number_format($totalToday, 0, ',', '.') }}
            </p>
        </div>

        <div class="card-editorial p-5">
            <span class="label-editorial !mb-1">Laba Bersih Hari Ini</span>
            <p class="text-2xl font-semibold text-blush-dark tabular-nums">
                Rp {{ number_format($profitToday, 0, ',', '.') }}
            </p>
        </div>

        <div class="card-editorial p-5">
            <span class="label-editorial !mb-1">Status Koleksi</span>
            <div class="flex items-baseline gap-3">
                <p class="text-2xl font-semibold tabular-nums">
                    {{ $availableProducts->count() }} <span class="text-xs font-normal text-taupe">tersedia</span>
                </p>
                @if($lowStockCount > 0)
                    <span class="text-xs px-2 py-0.5 rounded badge-stock-low">
                        {{ $lowStockCount }} menipis
                    </span>
                @endif
            </div>
        </div>
    </div>

    <form action="{{ route('products.index') }}" method="GET" class="mb-8">
        <div class="flex gap-2 max-w-md">
            <input type="text" name="search" value="{{ $keyword }}"
                   placeholder="Cari nama gelang, charm, atau aksesoris..."
                   class="input-editorial !bg-white">
            <button type="submit" class="btn-outline-sand">Cari</button>
            @if($keyword)
                <a href="{{ route('products.index') }}" class="px-3 py-2 text-xs text-taupe hover-text-espresso flex items-center">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <section class="mb-12">
        <div class="border-b border-sand pb-3 mb-6">
            <h2 class="font-editorial text-2xl">Ready Stock ({{ $availableProducts->count() }})</h2>
        </div>

        @if($availableProducts->isEmpty())
            <div class="card-editorial p-12 text-center">
                <p class="font-editorial italic text-xl text-taupe">Belum ada produk tersedia.</p>
                <a href="{{ route('products.create') }}" class="inline-block mt-3 text-xs uppercase tracking-wider text-blush-dark hover:underline">
                    Tambah koleksi pertama &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($availableProducts as $product)
                    <article class="card-editorial overflow-hidden flex flex-col justify-between">
                        <div>
                            <a href="{{ route('products.show', $product) }}" class="block relative aspect-[4/5] bg-ivory overflow-hidden group">
                                @if($product->image)
                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-[1.02] transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-taupe/50 font-editorial italic text-lg">
                                        teman sejarii
                                    </div>
                                @endif

                                <!-- Badge Stok Lebih Besar & Jelas -->
                                <span class="absolute top-3 right-3 px-3 py-1.5 text-sm font-semibold rounded-md tabular-nums
                                    {{ $product->stock <= 5 ? 'badge-stock-low' : 'badge-stock' }}">
                                    Stok: <span class="text-base font-bold">{{ $product->stock }}</span>
                                </span>
                            </a>

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="font-editorial text-xl font-semibold hover-text-blush transition leading-snug">
                                        {{ $product->name }}
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="text-xs text-taupe hover-text-espresso underline shrink-0">
                                        Edit
                                    </a>
                                </div>

                                <div class="mt-2 flex items-baseline justify-between text-sm tabular-nums">
                                    <span class="font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="text-xs text-taupe">Modal: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 pb-5 pt-3 border-t border-sand/70 space-y-2">
                            <!-- Form Terjual (Input Kosong dengan Placeholder) -->
                            <form action="{{ route('products.stock-out', $product) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" name="quantity" min="1" max="{{ $product->stock }}" placeholder="Qty" required
                                       class="input-editorial !w-16 !px-2 !py-1.5 !text-xs text-center tabular-nums">
                                <button type="submit" class="btn-espresso flex-1 !py-1.5 !text-xs">
                                    Catat Terjual
                                </button>
                            </form>

                            <!-- Form Restock -->
                            <form action="{{ route('products.stock-in', $product) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="number" name="quantity" min="1" placeholder="Qty" required
                                       class="input-editorial !bg-white !w-16 !px-2 !py-1.5 !text-xs text-center tabular-nums">
                                <button type="submit" class="btn-blush flex-1 !py-1.5 !text-xs">
                                    + Restock
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    @if($emptyProducts->isNotEmpty())
        <section>
            <div class="border-b border-sand pb-3 mb-6">
                <h2 class="font-editorial text-2xl text-taupe">Sold Out ({{ $emptyProducts->count() }})</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($emptyProducts as $product)
                    <div class="card-editorial !bg-white/75 p-4 flex flex-col justify-between gap-3">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <a href="{{ route('products.show', $product) }}" class="font-editorial text-lg hover:underline">
                                    {{ $product->name }}
                                </a>
                                <span class="badge-sand">Habis</span>
                            </div>
                            <p class="text-xs text-taupe tabular-nums mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>

                        <form action="{{ route('products.stock-in', $product) }}" method="POST" class="flex gap-2 pt-2 border-t border-sand/60">
                            @csrf
                            <input type="number" name="quantity" min="1" placeholder="Qty" required
                                   class="input-editorial !w-16 !px-2 !py-1 !text-xs text-center tabular-nums">
                            <button type="submit" class="btn-outline-sand flex-1 !py-1 !px-2 !text-xs hover:!bg-[#F5E6E8]">
                                + Restock
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
@endsection