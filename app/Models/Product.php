<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'stock',
        'price',
        'cost_price',
        'description',
        'image',
    ];

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
}