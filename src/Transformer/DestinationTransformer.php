<?php

namespace App\Transformer;

use App\Entity\Destination;

class DestinationTransformer
{
    public function toArray(Destination $destination): array
    {
        return [
            'id' => $destination->getId(),
            'name' => $destination->getName()
        ];
    }
}
