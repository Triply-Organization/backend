<?php

namespace App\Service;

use App\Mapper\TourRequestToTour;
use App\Mapper\TourUpdateRequestToTour;
use App\Repository\TourRepository;
use App\Entity\Tour;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;

class TourService
{
    private TourRepository $tourRepository;
    private TourRequestToTour $tourRequestToTour;
    private TourUpdateRequestToTour $tourUpdateRequestToTour;
    private TourPlanService $tourPlanService;
    private TourImageService $tourImageService;
    private TourServiceService $tourServiceService;
    private TourImageRepository $tourImageRepository;
    private TourPlanRepository $tourPlanRepository;

    public function __construct(
        TourRepository          $tourRepository,
        TourImageRepository     $tourImageRepository,
        TourPlanRepository      $tourPlanRepository,
        TourRequestToTour       $tourRequestToTour,
        TourUpdateRequestToTour $tourUpdateRequestToTour,
        TourPlanService         $tourPlanService,
        TourImageService        $tourImageService,
        TourServiceService      $tourServiceService
    )
    {
        $this->tourRequestToTour = $tourRequestToTour;
        $this->tourRepository = $tourRepository;
        $this->tourUpdateRequestToTour = $tourUpdateRequestToTour;
        $this->tourPlanService = $tourPlanService;
        $this->tourImageService = $tourImageService;
        $this->tourServiceService = $tourServiceService;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourPlanRepository = $tourPlanRepository;
    }

    public function findAll(ListTourRequest $listTourRequest): array
    {
        return $this->tourRepository->getAll($listTourRequest);
    }

    public function getCover(Tour $tour): string
    {
        $tourImages = $this->tourImageRepository->findBy(['tour' => $tour]);
        $path = '';
        foreach ($tourImages as $tourImage) {
            if ($tourImage->getType() === 'cover') {
                $path = $tourImage->getImage()->getPath();
            }
        }

        return $path;
    }

    public function delete(Tour $tour): void
    {
        $this->tourPlanRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourImageRepository->deleteWithRelation('tour', $tour->getId());

        $this->tourRepository->delete($tour->getId());
    }

    public function undoDelete(Tour $tour): void
    {
        $this->tourPlanRepository->undoDeleteWithRelation('tour', $tour->getId());

        $this->tourImageRepository->undoDeleteWithRelation('tour', $tour->getId());

        $this->tourRepository->undoDelete($tour->getId());
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
