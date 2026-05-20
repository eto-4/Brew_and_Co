<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa una línia d'una comanda.
 *
 * Cada línia conté un producte concret dins d'una comanda,
 * amb la seva quantitat i preu unitari.
 */
class OrderLine extends Model
{
    protected $table = 'linies_comanda';

    protected $fillable = [
        'comanda_id',
        'producte_id',
        'quantitat',
        'preu_unitari',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preu_unitari' => 'decimal:2',
        ];
    }

    /**
     * Obté la comanda a la qual pertany aquesta línia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'comanda_id');
    }

    /**
     * Obté el producte associat a aquesta línia de comanda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'producte_id');
    }
}