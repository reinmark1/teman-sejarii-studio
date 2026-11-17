<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'stock', 'price', 'description', 'image', 'cost_price'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
}
