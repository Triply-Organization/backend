<?php

namespace App\Service;

use App\Repository\TourRepository;
use App\Entity\Tour;
use App\Request\TourRequest;
use App\Repository\TourImageRepository;
use App\Repository\TourPlanRepository;

class TourService
{
    private TourPlanRepository $tourPlanRepository;
    private TourRepository $tourRepository;
    private TourImageRepository $tourImageRepository;

    public function __construct(
        TourRepository      $tourRepository,
        TourImageRepository $tourImageRepository,
        TourPlanRepository  $tourPlanRepository,
    )
    {
        $this->tourRepository = $tourRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourPlanRepository = $tourPlanRepository;
    }

    public function findAll(TourRequest $tourRequest): array
    {
        return $this->tourRepository->getAll($tourRequest);
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
}
