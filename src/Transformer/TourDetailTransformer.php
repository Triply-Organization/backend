<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Service\TourService;

class TourDetailTransformer extends BaseTransformer
{

    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private TourService $tourService;

    public function __construct(TourService $tourService)
    {
        $this->tourService = $tourService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourService->getGallary($tour);
        $result['tourPlans'] = $this->tourService->getTourPlan($tour);
        $result['services'] = $this->tourService->getServices($tour);

        return $result;
    }
}
