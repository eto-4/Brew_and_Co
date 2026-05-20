<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa un producte del sistema.
 *
 * Gestiona els productes emmagatzemats a la taula `productes`,
 * incloent informació com el nom, descripció, preu, imatge i
 * si està disponible o destacat.
 */
class Product extends Model
{
    protected $table = 'productes';

    protected $fillable = [
        'nom',
        'descripcio',
        'preu',
        'imatge',
        'disponible',
        'destacat',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
            'destacat'   => 'boolean',
            'preu'       => 'decimal:2',           
        ];
    }

    /**
     * Obté les ofertes associades a aquest producte.
     *
     * Relació molts-a-molts amb el model Offer a través de la taula
     * pivots `producte_oferta`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'producte_oferta', 'producte_id', 'oferta_id');
    }

    /**
     * Obté les línies de comanda que contenen aquest producte.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'producte_id');
    }
    
    /**
     * Obté les categories associades a aquest producte.
     *
     * Relació molts-a-molts amb el model Category a través de la taula
     * pivots `categoria_producte`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categoria_producte', 'producte_id', 'categoria_id');
    }
}