<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Produk</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>


    <div class="container">
        <a href="{{ route('products.create') }}" class="btn-tambah">+ Tambah Produk</a>

        <form method="GET" action="{{ route('products.index') }}" class="search-form">
            <input type="text" name="search" value="{{ $keyword ?? '' }}" placeholder="Cari nama produk..." />
            <button type="submit">Cari</button>
        </form>

        <h2>Produk Tersedia</h2>
        <table class="produk-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Stok Keluar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($availableProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="produk-img">
                        @else
                            Tidak ada gambar
                        @endif
                    </td>
                    <td>{{ $product->stock }}</td>
                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('products.stock-out', $product->id) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" min="1" required placeholder="Jumlah">
                            <input type="text" name="note" placeholder="Keterangan (opsional)">
                            <button type="submit">Stok Keluar</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}">Edit</a> |
                        <a href="{{ route('products.show', $product->id) }}">Detail</a> |
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h2 class="judul-habis">Produk Habis</h2>
        <table class="produk-table habis">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Stok Keluar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emptyProducts as $product)
                <tr>
                    <td><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></td>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar {{ $product->name }}" class="produk-img">
                        @else
                            <span>Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $product->stock }}</td>
                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>Stok habis</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}">Edit</a> |
                        <a href="{{ route('products.show', $product->id) }}">Detail</a> |
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('logout') }}" class="btn-tambah" style="background-color: #e74c3c;">Logout</a>

</body>
</html>
