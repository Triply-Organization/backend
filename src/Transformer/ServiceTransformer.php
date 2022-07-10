<?php

namespace App\Transformer;

use App\Entity\Service;

class ServiceTransformer extends BaseTransformer
{
    public function toArray(Service $Service)
    {
        return [
            'id' => $Service->getId(),
            'name' => $Service->getName(),
        ];
    }
}
