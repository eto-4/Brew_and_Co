<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model que representarà una direcció associada a un usuari.
 * 
 * Gestiona les direccions emmagatzemades a la taula 'adreces',
 * incloent informació de ubicació i si la direcció es
 * la predeterminada de l'usuari
 * */ 
class Address extends Model
{
    protected $table = 'adreces';

    protected $fillable = [
        'etiqueta',
        'adreca',
        'codi_postal',
        'ciutat',
        'predeterminada',
    ];

    /**
     * Defineix les conversions automàtiques de atributs del model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'predeterminada' => 'boolean',
        ];
    }

    /**
     * Obté l'usuari propietari d'una direcció.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }
}
