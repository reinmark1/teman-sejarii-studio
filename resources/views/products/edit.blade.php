@extends('layouts.app')

@section('title', 'Edit ' . $product->name . ' — teman sejarii')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-taupe mb-1">Perbarui Informasi</p>
            <h1 class="font-editorial text-3xl sm:text-4xl font-semibold">Edit Koleksi</h1>
            <p class="text-xs text-taupe mt-1">{{ $product->name }}</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-xs text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="card-editorial p-6 sm:p-8 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="label-editorial">Nama Koleksi / Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="input-editorial">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label-editorial">Harga Jual (Rp)</label>
                    <input type="number" name="price" step="1" value="{{ old('price', (int) $product->price) }}" min="0" required
                           class="input-editorial tabular-nums">
                </div>

                <div>
                    <label class="label-editorial">Harga Modal (Rp)</label>
                    <input type="number" name="cost_price" step="1" value="{{ old('cost_price', (int) $product->cost_price) }}" min="0" required
                           class="input-editorial tabular-nums">
                </div>
            </div>

            <div class="p-4 rounded-md bg-ivory border border-sand space-y-3">
                <div class="flex items-center justify-between">
                    <span class="label-editorial !mb-0">Penyesuaian Stok</span>
                    <span class="text-xs font-semibold tabular-nums">Stok Saat Ini: {{ $product->stock }} unit</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-taupe block mb-1">Tambah Stok Masuk (+)</label>
                        <input type="number" name="stock_in" min="0" placeholder="0" class="input-editorial !bg-white tabular-nums">
                    </div>

                    <div>
                        <label class="text-xs text-taupe block mb-1">Koreksi Pengurangan Stok (-)</label>
                        <input type="number" name="stock_out" min="0" placeholder="0" class="input-editorial !bg-white tabular-nums">
                    </div>
                </div>
            </div>

            <div>
                <label class="label-editorial">Deskripsi</label>
                <textarea name="description" rows="3" class="input-editorial">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="label-editorial">Ganti Foto Produk (Biarkan kosong jika tetap)</label>
                <div class="flex items-start gap-4 mt-2">
                    <div id="preview-wrapper" class="{{ $product->image ? '' : 'hidden' }} w-20 aspect-[4/5] rounded-md overflow-hidden border border-sand shrink-0">
                        <img id="preview-img" src="{{ $product->image ? asset('images/' . $product->image) : '' }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <input type="file" name="image" accept="image/*" data-image-input class="file-input-editorial">
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-between">
                <button type="button" data-delete-trigger class="text-xs text-red-700 hover:underline">
                    Hapus Produk
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('products.index') }}" class="btn-outline-sand">Batal</a>
                    <button type="submit" class="btn-espresso">Simpan Perubahan</button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-form.js') }}"></script>
@endpush