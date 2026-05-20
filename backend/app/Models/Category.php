<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa una categoria de productes.
 *
 * Gestiona les categories emmagatzemades a la taula `categories`,
 * incloent el seu nom, descripció i si està activa o no.
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'descripcio',
        'activa',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
        ];
    }

    /**
     * Obté els productes associats a aquesta categoria.
     *
     * Relació molts-a-molts amb el model Product a través de la taula
     * pivots `categoria_producte`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'categoria_producte', 'categoria_id', 'producte_id');
    }
}
