<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa un pagament d'una comanda.
 *
 * Gestiona els pagaments emmagatzemats a la taula `pagaments`,
 * incloent el mètode de pagament, estat i possibles codis de descompte.
 */
class Payment extends Model
{
    protected $table = 'pagaments';
    
    protected $fillable = [
        'comanda_id',
        'metode',
        'estat',
        'descripcio',
        'codi_descompte_id',
    ];

    /**
     * Obté la comanda associada a aquest pagament.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'comanda_id');
    }

    /**
     * Obté el codi de descompte aplicat al pagament, si existeix.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'codi_descompte_id');
    }
}