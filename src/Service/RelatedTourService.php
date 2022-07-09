<?php

namespace App\Service;

use App\Entity\TourPlan;
use App\Repository\DestinationRepository;
use App\Repository\TourRepository;
use App\Transformer\TourTransformer;

class RelatedTourService
{
    private TourRepository $tourRepository;
    private TourTransformer $tourTransformer;

    public function __construct(
        TourRepository $tourRepository,
        TourTransformer $tourTransformer
    ) {
        $this->tourTransformer = $tourTransformer;
        $this->tourRepository = $tourRepository;
    }

    public function getRelatedTour(int $destinationId, int $tourId): array
    {
        $results = [];
        $tours = $this->tourRepository->getTourWithDestination($destinationId, $tourId);
        foreach ($tours as $tour) {
            $results [] = $this->tourTransformer->toArray($tour);
        }

        return $results;
    }
}
