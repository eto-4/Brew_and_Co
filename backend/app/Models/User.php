<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model d'usuari autenticable del sistema.
 *
 * Gestiona els usuaris de la taula `usuaris`, incloent autenticació,
 * rols i relacions amb comandes i adreces.
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuaris';

    protected $fillable = [
        'nom',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * Defineix les conversions automàtiques dels atributs del model.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Determina si l'usuari té rol d'administrador.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Obté les comandes associades a l'usuari.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'usuari_id');
    }
    
    /**
     * Obté les adreces associades a l'usuari.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adreces()
    {
        return $this->hasMany(Address::class, 'usuari_id');
    }
}