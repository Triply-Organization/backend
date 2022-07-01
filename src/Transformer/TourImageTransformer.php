<?php

namespace App\Transformer;

use App\Entity\TourImage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TourImageTransformer
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function toArray(TourImage $tourImage): array
    {
        return [
            'id' => $tourImage->getId(),
            'path' => $this->params->get('s3url') . $tourImage->getImage()->getPath(),
            'type' => $tourImage->getType()
        ];
    }
}
