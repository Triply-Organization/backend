<?php

namespace App\Transformer;

use App\Entity\TourImage;

class TourImageCoverTransformer extends BaseTransformer
{

    public function toArray(TourImage $tourImage): array
    {
        return [
            'path' => $tourImage->getImage()->getPath(),
        ];
    }
}
