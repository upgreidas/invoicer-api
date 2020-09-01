<?php

namespace App\Services;

use App\Customer;

class CustomerService extends BaseService
{
    public function model()
    {
        return Customer::class;
    }
}
