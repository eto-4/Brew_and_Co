<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'ofertes';

    protected $fillable = [
        'preu_rebaixat',
        'data_inici',
        'data_fi',
    ];

    protected function casts(): array
    {
        return [
            'preu_rebaixat' => 'decimal:2',
            'data_inici'    => 'datetime',
            'data_fi'       => 'datetime',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'producte_oferta', 'oferta_id', 'producte_id');
    }
}