<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="{{ asset('css/style2.css') }}">
</head>
<body class="edit-page">

    <div class="navbar">
        <!-- Tambahkan logo di sini jika ingin -->
        <span class="logo">Teman Sejari</span>
    </div>

    <div class="form-container">
        <h1>Edit Produk: {{ $product->name }}</h1>

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="product-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Produk:</label>
                <input type="text" name="name" value="{{ $product->name }}" required>
            </div>

            <div class="form-group">
                <label>Harga Jual:</label>
                <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>
            </div>

            <div class="form-group">
                <label>Harga Modal:</label>
                <input type="number" name="cost_price" step="0.01" value="{{ $product->cost_price }}" required>
            </div>

            <div class="form-group">
                <label>Stok Saat Ini:</label>
                <input type="text" value="{{ $product->stock }} unit" disabled>
            </div>

            <div class="form-group">
                <label>Tambah Stok Masuk:</label>
                <input type="number" name="stock_in" min="0" placeholder="0">
            </div>

            <div class="form-group">
                <label>Revisi/Pengembalian Stok (koreksi keluar):</label>
                <input type="number" name="stock_out" min="0" placeholder="0">
            </div>

            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="description">{{ $product->description }}</textarea>
            </div>

            <button type="submit" class="btn-tambah">Simpan Perubahan</button>
        </form>

        <br>
        <a href="{{ route('products.index') }}">← Kembali ke Daftar Produk</a>
    </div>

</body>
</html>
