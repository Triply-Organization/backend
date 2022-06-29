<?php

namespace App\Transformer;

use App\Entity\Tour;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];

    public function fromArray(Tour $tour): array
    {
        $tourData = $this->transform($tour, static::PARAMS);
        $tourData['createUser'] = $tour->getCreatedUser()->jsonParse();
        return $tourData;
    }
}
