<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'descripcio',
        'activa',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'categoria_producte', 'categoria_id', 'producte_id');
    }
}