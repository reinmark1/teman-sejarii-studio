<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="navbar">
    <div class="navbar-left">
        <strong>LOGO</strong>
    </div>
    <div class="navbar-right">
        <form action="{{ route('logout') }}" method="GET">
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<div class="form-container">
    <h1>Tambah Produk</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
        @csrf

        <label>Nama Produk</label>
        <input type="text" name="name" required>

        <label>Harga Jual</label>
        <input type="number" name="price" required>

        <label>Harga Modal</label>
        <input type="number" name="cost_price" required>

        <label>Stok Awal</label>
        <input type="number" name="stock" required>

        <label>Deskripsi</label>
        <textarea name="description"></textarea>

        <label>Gambar Produk</label>
        <input type="file" name="image">

        <button type="submit" class="btn-tambah">Tambah Produk</button>
    </form>


    <br>
    <a href="{{ route('products.index') }}">← Kembali ke Daftar Produk</a>
</div>

</body>
</html>
