<?php

namespace App\Service;

use App\Mapper\TourRequestToTour;
use App\Repository\TourRepository;
use App\Request\TourRequest;

class TourService
{
    private TourRequestToTour $tourRequestToTour;
    private TourRepository $tourRepository;

    public function __construct(
        TourRequestToTour $tourRequestToTour,
        TourRepository    $tourRepository,
    )
    {
        $this->tourRequestToTour = $tourRequestToTour;
        $this->tourRepository = $tourRepository;
    }

    public function addTour(TourRequest $tourRequest)
    {
        $tour = $this->tourRequestToTour->mapper($tourRequest);
        $this->tourRepository->add($tour, true);
        return $tour;
    }
}
