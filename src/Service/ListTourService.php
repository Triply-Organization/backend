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
    private TicketTypeService $ticketTypeService;

    public function __construct(
        TourRepository     $tourRepository,
        TourTransformer    $tourTransformer,
        FacilityService    $facilityService,
        DestinationService $destinationService,
        TicketTypeService  $ticketTypeService
    )
    {
        $this->tourTransformer = $tourTransformer;
        $this->tourRepository = $tourRepository;
        $this->facilityService = $facilityService;
        $this->destinationService = $destinationService;
        $this->ticketTypeService = $ticketTypeService;
    }

    public function findAll(ListTourRequest $listTourRequest): array
    {
        $result = [];
        $tours = $this->tourRepository->getAll($listTourRequest);
        foreach ($tours as $tour) {
            $result[] = $this->tourTransformer->toArray($tour);
        }
        $result['destinations'] = $this->destinationService->getAllDestination();
        $result['services'] = $this->facilityService->getAllService();
        $result['tickets'] = $this->ticketTypeService->getTicket();

        if ($result === null) {
            $result = [];
        }

        return $result;
    }
}
