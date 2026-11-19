<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'teman sejarii — Studio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>
<body class="min-h-screen flex flex-col">

    <header class="bg-white/95 backdrop-blur-sm border-b border-sand sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('products.index') }}" class="flex items-baseline gap-2">
                    <span class="font-editorial italic text-2xl font-semibold text-espresso">teman sejarii</span>
                    <span class="text-[11px] uppercase tracking-[0.2em] text-taupe hidden sm:inline">Studio</span>
                </a>

                <nav class="flex items-center gap-1 sm:gap-2">
                    <a href="{{ route('products.index') }}"
                       class="px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('products.*') ? 'bg-blush-soft text-espresso font-medium' : 'text-taupe hover-text-espresso' }}">
                        Katalog &amp; Stok
                    </a>
                    <a href="{{ route('reports.index') }}"
                       class="px-3 py-1.5 rounded-md text-sm transition {{ request()->routeIs('reports.*') ? 'bg-blush-soft text-espresso font-medium' : 'text-taupe hover-text-espresso' }}">
                        Laporan Penjualan
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-xs text-taupe hidden md:inline">{{ auth()->user()->name ?? 'Owner' }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-outline-sand !py-1.5 !px-3 !text-xs uppercase tracking-wider text-taupe">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 py-8">
        @if(session('success'))
            <div class="mb-6 px-4 py-3 card-editorial border-l-4 border-blush text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-taupe hover-text-espresso text-xs ml-4">Tutup</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 px-4 py-3 rounded-md bg-red-50/80 border border-red-200 border-l-4 border-l-red-400 text-sm text-red-900 flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-700 text-xs ml-4">Tutup</button>
            </div>
        @endif

        @yield('content')
    </main>

    @unless(request()->routeIs('products.index'))
        <a href="{{ route('products.index') }}"
           onclick="if (document.referrer.startsWith(window.location.origin) && window.history.length > 1) { event.preventDefault(); window.history.back(); }"
           class="floating-back-btn"
           title="Kembali ke halaman sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blush-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali</span>
        </a>
    @endunless

    <footer class="border-t border-sand py-5 text-center text-xs text-taupe">
        teman sejarii &middot; jewelry &amp; trinkets inventory system
    </footer>

    @stack('scripts')
</body>
</html>