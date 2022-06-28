<?php

namespace App\Service;

use App\Repository\TourRepository;
use App\Entity\Tour;
use App\Request\TourRequest;

class TourService
{
    private TourRepository $tourRepository;

    public function __construct(TourRepository $tourRepository)
    {
        $this->tourRepository = $tourRepository;
    }

    public function findAll(TourRequest $tourRequest): array
    {
        return $this->tourRepository->getAll($tourRequest);
    }
}
