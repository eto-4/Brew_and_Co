<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    protected function casts(): array 
    {
        return [
            'temps_estimat' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    public function user() 
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }

    public function adreca()
    {
        return $this->belongsTo(Address::class, 'adreca_id');
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class, 'comanda_id');    
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'comanda_id');
    }
}