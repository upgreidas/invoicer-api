<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'description', 'pretax_price', 'taxes'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->price = $item->pretax_price + $item->taxes;
        });
    }
}