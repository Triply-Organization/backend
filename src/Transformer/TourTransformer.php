<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Entity\TourImage;
use App\Entity\TourPlan;
use App\Repository\TourRepository;
use App\Service\TourService;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private TourService $tourService;

    public function __construct(
        TourService $tourService
    )
    {
        $this->tourService = $tourService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourService->getCover($tour);

        return $result;
    }
}
