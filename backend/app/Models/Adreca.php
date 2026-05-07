<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adreca extends Model
{
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
        return $this->belongsTo(User::class);
    }
}