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
        $thisTime = new \DateTimeImmutable();
        $tourPlans = $this->tourPlanRepository->findBy(['tour' => $tour]);
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);

        foreach ($tourPlans as $tourPlan) {
            $tourPlan->setDeletedAt($thisTime);
            $this->tourPlanRepository->add($tourPlan, true);
        }

        foreach ($tourImages as $tourImage) {
            $tourImage->setDeletedAt($thisTime);
            $this->tourImageRepository->add($tourImage, true);
        }

        $tour->setDeletedAt($thisTime);
        $this->tourRepository->add($tour, true);
    }
}
