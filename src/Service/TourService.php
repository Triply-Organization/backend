<?php

namespace App\Service;

use App\Mapper\TourCreateMapper;
use App\Mapper\TourUpdateMapper;
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
    private TourCreateMapper $tourCreateMapper;
    private TourUpdateMapper $tourUpdateMapper;
    private TourPlanService $tourPlanService;
    private TourImageService $tourImageService;
    private FacilityTourService $facilityTourService;
    private TourImageRepository $tourImageRepository;
    private TourPlanRepository $tourPlanRepository;

    public function __construct(
        TourRepository      $tourRepository,
        TourImageRepository $tourImageRepository,
        TourPlanRepository  $tourPlanRepository,
        TourCreateMapper    $tourCreateMapper,
        TourUpdateMapper    $tourUpdateMapper,
        TourPlanService     $tourPlanService,
        TourImageService    $tourImageService,
        FacilityTourService $facilityTourService
    )
    {
        $this->tourCreateMapper = $tourCreateMapper;
        $this->tourRepository = $tourRepository;
        $this->tourUpdateMapper = $tourUpdateMapper;
        $this->tourPlanService = $tourPlanService;
        $this->tourImageService = $tourImageService;
        $this->facilityTourService = $facilityTourService;
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
            if ($tourImage->getType() === 'COVER') {
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
        $tour = $this->tourCreateMapper->mapping($tourRequest);
        if ($tourRequest->getTourImages() !== null) {
            $this->tourImageService->addTourImage($tourRequest, $tour);
        }
        if ($tourRequest->getTourPlans() !== null) {
            $this->tourPlanService->addTourPlan($tourRequest, $tour);
        }
        if ($tourRequest->getServices() !== null) {
            $this->facilityTourService->addServiceToTour($tourRequest, $tour);
        }
        $this->tourRepository->add($tour, true);

        return $tour;
    }

    public function updateTour(Tour $tour, TourUpdateRequest $tourUpdateRequest): Tour
    {
        $tourUpdateMapper = $this->tourUpdateMapper->mapping($tour, $tourUpdateRequest);
        if ($tourUpdateRequest->getTourPlans()) {
            $this->tourPlanService->updateTourPlan($tourUpdateRequest);
        }
        if ($tourUpdateRequest->getServices()) {
            $this->facilityTourService->updateServiceFromTour($tour, $tourUpdateRequest);
        }
        if ($tourUpdateRequest->getTourImages()) {
            $this->tourImageService->updateTourImage($tour, $tourUpdateRequest);
        }
        $this->tourRepository->add($tourUpdateMapper, true);

        return $tour;
    }
}
