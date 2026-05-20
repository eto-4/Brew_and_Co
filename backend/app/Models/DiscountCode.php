<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representa un codi de descompte.
 *
 * Gestiona els codis emmagatzemats a la taula `codis_descompte`,
 * incloent el percentatge de descompte, si està actiu i la data
 * de caducitat.
 */
class DiscountCode extends Model
{
    protected $table = 'codis_descompte';

    protected $fillable = [
        'codi',
        'percentatge',
        'actiu',
        'expires_at',
    ];

    /**
     * Defineix les conversions automàtiques dels atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'percentatge' => 'decimal:2',
            'actiu'       => 'boolean',
            'expires_at'  => 'datetime',
        ];
    }
    /**
     * Obté els pagaments associats a aquest codi de descompte.
     *
     * Relació d'un a molts amb el model Payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'codi_descompte_id');
    }
}