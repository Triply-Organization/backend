<?php

namespace App\Transformer;

use App\Entity\Service;
use App\Entity\Tour;
use App\Entity\TourService;

class TourServicesTransformer extends BaseTransformer
{
    public function toArray(TourService $tourService)
    {
        return [
            'id' => $tourService->getService()->getId(),
            'name' => $tourService->getService()->getName()
        ];
    }
}
