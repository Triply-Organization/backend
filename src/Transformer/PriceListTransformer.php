<?php

namespace App\Transformer;

use App\Entity\PriceList;

class PriceListTransformer
{
    public function toArray(PriceList $priceList)
    {
        return [
            'type' => $priceList->getType()->getName(),
            'price' => $priceList->getPrice(),
        ];
    }
}