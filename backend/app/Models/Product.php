<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nom',
        'descripcio',
        'preu',
        'categoria',
        'imatge',
        'disponible',
        'destacat',
    ];

    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
            'destacat'   => 'boolean',
            'preu'       => 'decimal:2',           
        ];
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }
}0