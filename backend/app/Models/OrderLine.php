<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $fillable = [
        'comanda_id',
        'producte_id',
        'quantitat',
        'preu_unitari',
    ];

    protected function casts(): array
    {
        return [
            'preu_unitari' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'comanda_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'producte_id');
    }
}