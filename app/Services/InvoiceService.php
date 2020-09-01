<?php

namespace App\Services;

use App\Item;
use Exception;
use App\Invoice;
use App\Customer;
use App\InvoiceSerie;
use Illuminate\Support\Arr;

class InvoiceService extends BaseService
{
    public function model()
    {
        return Invoice::class;
    }

    public function create($data)
    {
        if (!Arr::has($data, 'customer_id')) {
            throw new Exception('Missing customer ID.');
        }

        $existingCustomer = Customer::find($data['customer_id']);

        if (!$existingCustomer) {
            throw new Exception('Customer ' . $data['customer_id'] . ' does not exist.');
        }
        
        if (!Arr::has($data, 'items') || count($data['items']) === 0) {
            throw new Exception('Missing items.');
        }
        
        if (!Arr::has($data, 'invoice_serie_id')) {
            throw new Exception('Missing invoice serie ID.');
        }

        $invoiceSerie = InvoiceSerie::find($data['invoice_serie_id']);

        if (!$invoiceSerie) {
            throw new Exception('Invoice serie ' . $data['invoice_serie_id'] . ' does not exist.');
        }
        
        if (!Arr::has($data, 'number')) {
            throw new Exception('Missing invoice number.');
        }

        $formattedInvoiceNumber = \format_invoice_number($invoiceSerie->prefix, $data['number']);

        $existingInvoice = $this->findByNumber($formattedInvoiceNumber);

        if ($existingInvoice) {
            throw new Exception('Invoice ' . $formattedInvoiceNumber . ' already exists.');
        }
        
        if (!Arr::has($data, 'date')) {
            throw new Exception('Missing invoice date.');
        }

        $invoice = new Invoice();
        $invoice->fill($data);
        $invoice->subtotal = 0;
        $invoice->taxes = 0;
        $invoice->total = 0;
        $invoice->number = $formattedInvoiceNumber;

        foreach ($data['items'] as $item) {
            if (!Arr::has($item, 'item_id')) {
                throw new Exception('Missing item ID.');
            }
            
            if (!Arr::has($item, 'quantity')) {
                throw new Exception('Missing item quantity.');
            }
            
            if (!Arr::has($item, 'unit_pretax_price')) {
                throw new Exception('Missing item unit pretax price.');
            }
            
            if (!Arr::has($item, 'unit_taxes')) {
                throw new Exception('Missing item unit taxes.');
            }

            $existingItem = Item::find($item['item_id']);

            if (!$existingItem) {
                throw new Exception('Item ' . $item['item_id'] . ' does not exist.');
            }

            $item['subtotal'] = $item['quantity'] * $item['unit_pretax_price'];
            $item['taxes'] = $item['quantity'] * $item['unit_taxes'];
            $item['total'] = $item['subtotal'] + $item['taxes'];

            $invoice->subtotal += $item['subtotal'];
            $invoice->taxes += $item['taxes'];
            $invoice->total += $item['total'];
        }

        $invoice->save();

        foreach ($data['items'] as $item) {
            $invoice->items()->create($item);
        }

        return $invoice->fresh();
    }

    public function findByNumber($number)
    {
        return Invoice::where('number', $number)->first();
    }
}
