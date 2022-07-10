<?php

namespace App\Transformer;

use App\Entity\Destination;
use App\Entity\TourPlan;

class DestinationTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'name'];

    public function toArray(Destination $destination): array
    {
        return $this->transform($destination, static::PARAMS);
    }

    public function listToArray(TourPlan|array $tourPlan): array
    {
        return [
            'id' => $tourPlan->getDestination()->getId(),
            'destination' => $tourPlan->getDestination()->getName(),
        ];
    }
}
