<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'productes';

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

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'producte_oferta', 'producte_id', 'oferta_id');
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'producte_id');
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categoria_producte', 'producte_id', 'categoria_id');
    }
}