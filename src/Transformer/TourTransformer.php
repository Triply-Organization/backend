<?php

namespace App\Transformer;

use App\Entity\Tour;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'tourPlans', 'services', 'price', 'tourImages'];

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourPlans'] = $tour->getTourPlans();
        $result['services'] = $tour->getServices();
        $result['tourImages'] = $tour->getTourImages();

        return $result;
    }
}
