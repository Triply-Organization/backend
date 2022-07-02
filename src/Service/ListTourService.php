<?php

namespace App\Service;

use App\Request\ListTourRequest;
use App\Repository\TourRepository;
use App\Transformer\TourTransformer;

class ListTourService
{


    private TourRepository $tourRepository;
    private TourTransformer $tourTransformer;
    private FacilityService $facilityService;
    private DestinationService $destinationService;

    public function __construct(
        TourRepository     $tourRepository,
        TourTransformer    $tourTransformer,
        FacilityService    $facilityService,
        DestinationService $destinationService
    )
    {
        $this->tourTransformer = $tourTransformer;
        $this->tourRepository = $tourRepository;
        $this->facilityService = $facilityService;
        $this->destinationService = $destinationService;
    }

    public function findAll(ListTourRequest $listTourRequest): array
    {
        $result = [];
        $tours = $this->tourRepository->getAll($listTourRequest);
        foreach ($tours as $tour) {
            $result[] = $this->tourTransformer->listToArray($tour);
        }
        $result['services'] = $this->facilityService->getAllService();
        $result['destinations'] = $this->destinationService->getAllDestination();

        return $result;
    }
}