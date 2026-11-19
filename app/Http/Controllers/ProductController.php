<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $query = Product::query();

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $products = $query->orderBy('name', 'asc')->get();
        $availableProducts = $products->where('stock', '>', 0);
        $emptyProducts = $products->where('stock', '<=', 0);
        $lowStockCount = $products->where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $todayLogs = StockLog::where('type', 'out')
            ->whereDate('created_at', today())
            ->get();

        $totalToday = $todayLogs->sum('total_price');
        $profitToday = $todayLogs->sum('profit');

        return view('products.index', compact(
            'availableProducts',
            'emptyProducts',
            'lowStockCount',
            'totalToday',
            'profitToday',
            'keyword'
        ));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
            }

            $product = Product::create([
                'name' => $request->name,
                'stock' => $request->stock,
                'price' => $request->price,
                'cost_price' => $request->cost_price,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            if ($product->stock > 0) {
                StockLog::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->stock,
                    'total_price' => $product->stock * $product->cost_price,
                    'profit' => 0,
                    'note' => 'Stok awal produk',
                ]);
            }
        });

        return redirect()->route('products.index')->with('success', 'Koleksi berhasil ditambahkan.');
    }

    public function stockOut(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $result = DB::transaction(function () use ($request, $product) {
                $lockedProduct = Product::where('id', $product->id)->lockForUpdate()->first();

                if ($lockedProduct->stock < $request->quantity) {
                    throw new \Exception('Stok tidak mencukupi (Sisa stok: ' . $lockedProduct->stock . ').');
                }

                $lockedProduct->decrement('stock', $request->quantity);

                $quantity = $request->quantity;
                $total = $quantity * $lockedProduct->price;
                $profit = $quantity * ($lockedProduct->price - ($lockedProduct->cost_price ?? 0));

                StockLog::create([
                    'product_id' => $lockedProduct->id,
                    'type' => 'out',
                    'quantity' => $quantity,
                    'total_price' => $total,
                    'profit' => $profit,
                    'note' => $request->note ?? 'Penjualan',
                ]);

                return ['total' => $total, 'profit' => $profit];
            });

            return back()->with('success', 'Penjualan tercatat. Omzet Rp ' . number_format($result['total'], 0, ',', '.') . ' | Laba Rp ' . number_format($result['profit'], 0, ',', '.'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function stockIn(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->increment('stock', $request->quantity);

            StockLog::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * ($product->cost_price ?? 0),
                'profit' => 0,
                'note' => $request->note ?? 'Restock barang masuk',
            ]);
        });

        return back()->with('success', 'Berhasil menambah ' . $request->quantity . ' stok untuk ' . $product->name . '.');
    }

    public function show(Product $product)
    {
        $transactions = StockLog::where('product_id', $product->id)
            ->where('type', 'out')
            ->orderBy('created_at', 'asc')
            ->get();

        $allLogs = StockLog::where('product_id', $product->id)
            ->latest()
            ->take(20)
            ->get();

        $transactionLabels = $transactions->map(fn($log) => $log->created_at->format('d M H:i'));
        $transactionQuantities = $transactions->pluck('quantity');
        $transactionProfits = $transactions->pluck('profit');

        $totalSold = $transactions->sum('quantity');
        $totalRevenue = $transactions->sum('total_price');
        $totalProfit = $transactions->sum('profit');

        return view('products.show', compact(
            'product',
            'allLogs',
            'transactionLabels',
            'transactionQuantities',
            'transactionProfits',
            'totalSold',
            'totalRevenue',
            'totalProfit'
        ));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock_in' => 'nullable|integer|min:0',
            'stock_out' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $product) {
            if ($request->hasFile('image')) {
                if ($product->image && File::exists(public_path('images/' . $product->image))) {
                    File::delete(public_path('images/' . $product->image));
                }
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $product->image = $imageName;
            }

            $product->name = $request->name;
            $product->price = $request->price;
            $product->cost_price = $request->cost_price;
            $product->description = $request->description;

            if ($request->filled('stock_in') && $request->stock_in > 0) {
                $product->stock += $request->stock_in;
                StockLog::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $request->stock_in,
                    'total_price' => $request->stock_in * $product->cost_price,
                    'profit' => 0,
                    'note' => 'Penambahan stok via Edit Produk',
                ]);
            }

            if ($request->filled('stock_out') && $request->stock_out > 0) {
                $actualOut = min($product->stock, $request->stock_out);
                $product->stock -= $actualOut;
                if ($actualOut > 0) {
                    StockLog::create([
                        'product_id' => $product->id,
                        'type' => 'correction',
                        'quantity' => $actualOut,
                        'total_price' => 0,
                        'profit' => 0,
                        'note' => 'Koreksi pengurangan stok via Edit Produk',
                    ]);
                }
            }

            $product->save();
        });

        return redirect()->route('products.index')->with('success', 'Koleksi berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path('images/' . $product->image))) {
            File::delete(public_path('images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Koleksi berhasil dihapus.');
    }

    public function reports(Request $request)
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        $logs = StockLog::with('product')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $salesLogs = $logs->where('type', 'out');
        $totalRevenue = $salesLogs->sum('total_price');
        $totalProfit = $salesLogs->sum('profit');
        $totalItemsSold = $salesLogs->sum('quantity');
        $totalRestockCost = $logs->where('type', 'in')->sum('total_price');

        return view('reports.index', compact(
            'logs',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalProfit',
            'totalItemsSold',
            'totalRestockCost'
        ));
    }
}