<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Tour;
use App\Entity\TourImage;
use App\Entity\TourPlan;
use App\Mapper\TourRequestToTour;
use App\Mapper\TourUpdateRequestToTour;
use App\Repository\DestinationRepository;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Repository\TourRepository;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;

class TourService
{
    private TourRequestToTour $tourRequestToTour;
    private TourRepository $tourRepository;
    private TourUpdateRequestToTour $tourUpdateRequestToTour;
    private TourPlanService $tourPlanService;
    private TourImageService $tourImageService;
    private TourServiceService $tourServiceService;

    public function __construct(
        TourRequestToTour       $tourRequestToTour,
        TourRepository          $tourRepository,
        TourUpdateRequestToTour $tourUpdateRequestToTour,
        TourPlanService         $tourPlanService,
        TourImageService        $tourImageService,
        TourServiceService $tourServiceService
    )
    {
        $this->tourRequestToTour = $tourRequestToTour;
        $this->tourRepository = $tourRepository;
        $this->tourUpdateRequestToTour = $tourUpdateRequestToTour;
        $this->tourPlanService = $tourPlanService;
        $this->tourImageService = $tourImageService;
        $this->tourServiceService = $tourServiceService;
    }

    public function addTour(TourRequest $tourRequest): Tour
    {
        $tourMapper = $this->tourRequestToTour->mapper($tourRequest);
        $tourImage = $this->tourImageService->addTourImage($tourRequest, $tourMapper);
        $tourService = $this->tourServiceService->addServiceToTour($tourRequest, $tourImage);
        $tour = $this->tourPlanService->addTourPlan($tourRequest, $tourService);
        $this->tourRepository->add($tour, true);

        return $tour;
    }

    public function updateTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): Tour
    {
        $tourUpdateMapper = $this->tourUpdateRequestToTour->mapper($tour, $tourUpdateRequest);
        if ($tourUpdateRequest->getTourPlans()) {
            $this->tourPlanService->updateTourPlan($tourUpdateRequest);
        }
        if ($tourUpdateRequest->getServices()) {
            $this->tourServiceService->updateServiceFromTour($tour, $tourUpdateRequest);
        }
        if ($tourUpdateRequest->getTourImages()) {
            $this->tourImageService->updateTourImage($tour, $tourUpdateRequest);
        }
        $this->tourRepository->add($tourUpdateMapper, true);

        return $tour;
    }
}
