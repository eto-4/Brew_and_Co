<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected function casts(): array
    {
        return [
            'predeterminada' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }
}