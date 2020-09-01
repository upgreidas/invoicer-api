<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id', 'invoice_serie_id', 'number', 'date', 'due_date', 'notes', 'subtotal', 'taxes', 'sent'
    ];

    protected static function booted()
    {
        static::creating(function ($invoice) {
            $invoice->total = $invoice->subtotal + $invoice->taxes;
        });
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
