<?php

namespace App\Transformer;

use App\Entity\PriceList;

class PriceListTransformer
{
    public function toArray(PriceList $priceList)
    {
        return [
            'id' => $priceList->getId(),
            'type' => $priceList->getType()->getName(),
            'price' => $priceList->getPrice(),
        ];
    }
}
