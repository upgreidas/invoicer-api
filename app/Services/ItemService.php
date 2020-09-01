<?php

namespace App\Services;

use App\Item;

class ItemService extends BaseService
{
    public function model()
    {
        return Item::class;
    }
}
