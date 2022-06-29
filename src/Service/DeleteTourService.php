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

    public function delete(Tour $tour)
    {
        $thisTime = new \DateTimeImmutable();
        $tourPlans = $this->tourPlanRepository->findBy(['tour_id' => $tour->getId()]);
        $tourImages = $this->tourImageRepository->findBy(['tour_id' => $tour->getId()]);
        $tour->setDeletedAt($thisTime);
        foreach ($tourPlans as $tourPlan) {
            $tourPlan->setDeletedAt($thisTime);
        }

        foreach ($tourImages as $tourImage) {
            $tourImage->setDeletedAt($thisTime);
        }
    }
}
