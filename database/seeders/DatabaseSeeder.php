<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@temansejarii.com'],
            [
                'name' => 'Owner teman sejarii',
                'password' => Hash::make('rahasia123'),
            ]
        );

        $items = [
            [
                'name' => 'Siren Pearl Bracelet',
                'stock' => 14,
                'price' => 45000,
                'cost_price' => 21000,
                'description' => 'Gelang mutiara air tawar dengan aksen rantai stainless anti-karat.',
            ],
            [
                'name' => 'Blush Ribbon Charm Link',
                'stock' => 4,
                'price' => 58000,
                'cost_price' => 28000,
                'description' => 'Italian charm bracelet edisi pita dusty pink dan kristal bening.',
                'image' => '1795080964_6afec30408968.jpg',
            ],
            [
                'name' => 'Petal Drop Ring Set',
                'stock' => 18,
                'price' => 35000,
                'cost_price' => 15000,
                'description' => 'Set 2 cincin manik bunga pastel dan emas muda.',
            ],
            [
                'name' => 'Dainty Heart Clasp Necklace',
                'stock' => 9,
                'price' => 65000,
                'cost_price' => 32000,
                'description' => 'Kalung choker tipis dengan pengait berbentuk hati.',
            ],
            [
                'name' => 'Midnight Starlight Charm',
                'stock' => 0,
                'price' => 52000,
                'cost_price' => 25000,
                'description' => 'Koleksi charm motif bintang malam (Sold Out batch pertama).',
            ],
        ];

        foreach ($items as $index => $item) {
            $product = Product::create($item);

            StockLog::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $item['stock'] + 6,
                'total_price' => ($item['stock'] + 6) * $item['cost_price'],
                'profit' => 0,
                'note' => 'Stok awal batch studio',
                'created_at' => now()->subDays(5 - $index),
            ]);

            for ($day = 3; $day >= 0; $day--) {
                $qty = rand(1, 3);
                StockLog::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $qty,
                    'total_price' => $qty * $item['price'],
                    'profit' => $qty * ($item['price'] - $item['cost_price']),
                    'note' => 'Penjualan reguler',
                    'created_at' => now()->subDays($day)->setHour(14 + $index),
                ]);
            }
        }
    }
}