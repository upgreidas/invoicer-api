<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    
    const INDIVIDUAL_TYPE = 'individual';

    const ORGANIZATION_TYPE = 'organization';

    protected $fillable = [
        'name', 'company_code', 'vat_code', 'country', 'address', 'phone_number', 'email', 'type'
    ];

}
