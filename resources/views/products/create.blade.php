@extends('layouts.app')

@section('title', 'Tambah Koleksi — teman sejarii')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <p class="text-xs uppercase tracking-[0.2em] text-taupe mb-1">Inventaris Studio</p>
            <h1 class="font-editorial text-3xl sm:text-4xl font-semibold">Tambah Koleksi Baru</h1>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-md bg-red-50 border border-red-200 text-xs text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="card-editorial p-6 sm:p-8 space-y-5">
            @csrf

            <div>
                <label class="label-editorial">Nama Koleksi / Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="input-editorial" placeholder="Contoh: Siren Pearl Bracelet">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="label-editorial">Harga Jual (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" required
                           class="input-editorial tabular-nums" placeholder="45000">
                </div>

                <div>
                    <label class="label-editorial">Harga Modal (Rp)</label>
                    <input type="number" name="cost_price" value="{{ old('cost_price') }}" min="0" required
                           class="input-editorial tabular-nums" placeholder="22000">
                </div>

                <div>
                    <label class="label-editorial">Stok Awal</label>
                    <input type="number" name="stock" value="{{ old('stock', 10) }}" min="0" required
                           class="input-editorial tabular-nums">
                </div>
            </div>

            <div>
                <label class="label-editorial">Deskripsi &amp; Material (Opsional)</label>
                <textarea name="description" rows="3" class="input-editorial"
                          placeholder="Detail ukuran, bahan manik/stainless, atau catatan kemasan...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="label-editorial">Foto Produk (Rasio 4:5 disarankan)</label>
                <input type="file" name="image" accept="image/*" data-image-input class="file-input-editorial">
                <div id="preview-wrapper" class="hidden mt-4 w-36 aspect-[4/5] rounded-md overflow-hidden border border-sand">
                    <img id="preview-img" src="" alt="Preview" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="pt-4 border-t border-sand flex items-center justify-end gap-3">
                <a href="{{ route('products.index') }}" class="btn-outline-sand">Batal</a>
                <button type="submit" class="btn-espresso">Simpan ke Katalog</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-form.js') }}"></script>
@endpush