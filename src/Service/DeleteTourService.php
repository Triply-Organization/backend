<?php

namespace App\Service;

use App\Entity\Tour;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Repository\TourRepository;

class DeleteTourService
{
    private TourRepository $tourRepository;
    private TourPlanRepository $tourPlanRepository;
    private TourImageRepository $tourImageRepository;

    public function __construct(
        TourRepository      $tourRepository,
        TourPlanRepository  $tourPlanRepository,
        TourImageRepository $tourImageRepository
    )
    {
        $this->tourRepository = $tourRepository;
        $this->tourPlanRepository = $tourPlanRepository;
        $this->tourImageRepository = $tourImageRepository;
    }

    public function delete(Tour $tour):void
    {
        $this->tourPlanRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourImageRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourRepository->delete($tour->getId());
    }
}
