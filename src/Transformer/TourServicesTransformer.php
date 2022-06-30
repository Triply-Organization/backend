<?php

namespace App\Transformer;

use App\Entity\Service;
use App\Entity\Tour;
use App\Service\TourService;

class TourServicesTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'name'];

    public function toArray(Service $service): array
    {
        return $this->transform($service, static::PARAMS);
    }
}