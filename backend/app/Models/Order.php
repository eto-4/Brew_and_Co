<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa una comanda del sistema.
 *
 * Gestiona les comandes emmagatzemades a la taula `comandes`,
 * incloent estat, total, adreça de lliurament i temps estimat.
 */
class Order extends Model
{
    protected $table = 'comandes';

    protected $fillable = [
        'adreca_id',
        'estat',
        'temps_estimat',
        'missatge',
        'total',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array 
    {
        return [
            'temps_estimat' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Obté l'usuari propietari de la comanda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() 
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }

    /**
     * Obté l'adreça associada a la comanda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adreca()
    {
        return $this->belongsTo(Address::class, 'adreca_id');
    }

    /**
     * Obté les línies de la comanda.
     *
     * Cada línia representa un producte dins la comanda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'comanda_id');    
    }

    /**
     * Obté els pagaments associats a la comanda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'comanda_id');
    }
}