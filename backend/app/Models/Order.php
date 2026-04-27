<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'usuari_id',
        'adreca_id',
        'estat',
        'total',
    ];
    protected function casts(): array 
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function user() 
    {
        return $this->belongsTo(User::class, 'usuari_id');
    }

    public function adreca()
    {
        return $this->belongsTo(Adreca::class, 'adreca_id');
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);    
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}