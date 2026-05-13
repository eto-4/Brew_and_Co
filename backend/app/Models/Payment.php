<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function order()
    {
        return $this->belongsTo(Order::class, 'comanda_id');
    }

    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'codi_descompte_id');
    }
}