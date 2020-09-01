<?php

namespace App\Services;

use App\InvoiceSerie;

class InvoiceSerieService extends BaseService
{
    public function model()
    {
        return InvoiceSerie::class;
    }
}
