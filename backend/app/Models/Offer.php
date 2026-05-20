<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa una oferta de productes.
 *
 * Gestiona les ofertes emmagatzemades a la taula `ofertes`,
 * incloent el preu rebaixat i el període de validesa.
 */
class Offer extends Model
{
    protected $table = 'ofertes';

    protected $fillable = [
        'preu_rebaixat',
        'data_inici',
        'data_fi',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preu_rebaixat' => 'decimal:2',
            'data_inici'    => 'datetime',
            'data_fi'       => 'datetime',
        ];
    }
    
    /**
     * Obté els productes associats a aquesta oferta.
     *
     * Relació molts-a-molts amb el model Product a través de la taula
     * pivots `producte_oferta`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'producte_oferta', 'oferta_id', 'producte_id');
    }
}