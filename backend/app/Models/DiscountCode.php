<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $table = 'codis_descompte';

    protected $fillable = [
        'codi',
        'percentatge',
        'actiu',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'percentatge' => 'decimal:2',
            'actiu'       => 'boolean',
            'expires_at'  => 'datetime',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'codi_descompte_id');
    }
}