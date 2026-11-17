<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $query = Product::query();

        if ($keyword) {
            $query->where('name', 'ILIKE', "%$keyword%");
        }

        $products = $query->get();
        $availableProducts = $products->where('stock', '>', 0);
        $emptyProducts = $products->where('stock', '<=', 0);

        $totalToday = StockLog::where('type', 'out')
            ->whereDate('created_at', today())
            ->sum('total_price');

        return view('products.index', compact('availableProducts', 'emptyProducts', 'totalToday', 'keyword'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Product::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'price' => $request->price,
            'cost_price' => $request->cost_price,
            'description' => $request->description,
            'image' => $imageName ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function stockOut(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $product->decrement('stock', $request->quantity);

        $sellingPrice = $product->price;
        $costPrice = $product->cost_price ?? 0;
        $quantity = $request->quantity;
        $total = $quantity * $sellingPrice;
        $profit = $quantity * ($sellingPrice - $costPrice);

        StockLog::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $quantity,
            'total_price' => $total,
            'profit' => $profit,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Stok keluar. Pendapatan Rp ' . number_format($total, 0, ',', '.') . ' | Keuntungan Rp ' . number_format($profit, 0, ',', '.'));
    }

    public function show(Product $product)
    {
        $transactions = StockLog::where('product_id', $product->id)
            ->where('type', 'out')
            ->orderBy('created_at', 'asc')
            ->get();

        $transactionLabels = $transactions->map(function ($log) {
            return \Carbon\Carbon::parse($log->created_at)->format('d M H:i');
        });

        $transactionQuantities = $transactions->pluck('quantity');
        $transactionProfits = $transactions->pluck('profit');

        $totalSold = $transactions->sum('quantity');
        $totalRevenue = $transactions->sum('total_price');
        $totalProfit = $transactions->sum('profit');

        return view('products.show', compact(
            'product',
            'transactionLabels',
            'transactionQuantities',
            'transactionProfits',
            'totalSold',
            'totalRevenue',
            'totalProfit'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'description' => 'nullable|string',
            'stock_in' => 'nullable|integer|min:0',
            'stock_out' => 'nullable|integer|min:0',
        ]);

        // Update data dasar
        $product->name = $request->name;
        $product->price = $request->price;
        $product->cost_price = $request->cost_price;
        $product->description = $request->description;

        // Tambah stok jika ada
        if ($request->filled('stock_in')) {
            $product->stock += $request->stock_in;
        }

        // Kurangi stok jika ada koreksi keluar
        if ($request->filled('stock_out')) {
            $product->stock = max(0, $product->stock - $request->stock_out);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
}
