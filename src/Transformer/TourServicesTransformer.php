<?php

namespace App\Transformer;

use App\Entity\Service;
use App\Entity\Tour;
use App\Service\TourService;

class TourServicesTransformer
{
    public function toArray(Service $service): array
    {
        return [
            'id' => $service->getId(),
            'name' => $service->getName()
        ];
    }
}
