<?php

namespace App\Service;

use App\Entity\Tour;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\ListTourRequest;
use App\Repository\TourRepository;
use App\Transformer\TourTransformer;
use Symfony\Component\Security\Core\Security;

class ListTourService
{
    private TourRepository $tourRepository;
    private TourTransformer $tourTransformer;
    private Security $security;
    private UserRepository $userRepository;
    private FacilityService $facilityService;
    private DestinationService $destinationService;
    private TicketTypeService $ticketTypeService;

    public function __construct(
        TourRepository $tourRepository,
        TourTransformer $tourTransformer,
        Security $security,
        UserRepository $userRepository,
        FacilityService $facilityService,
        DestinationService $destinationService,
        TicketTypeService $ticketTypeService
    ) {
        $this->tourTransformer = $tourTransformer;
        $this->tourRepository = $tourRepository;
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->facilityService = $facilityService;
        $this->destinationService = $destinationService;
        $this->ticketTypeService = $ticketTypeService;
    }

    public function findAll(ListTourRequest $listTourRequest): array
    {
        $result = [];
        $data = $this->tourRepository->getAll($listTourRequest);
        $tours = $data['tours'];
        foreach ($tours as $tour) {
            $result ['tours'][] = $this->tourTransformer->toArray($tour);
        }
        $result['destinations'] = $this->destinationService->getAllDestination();
        $result['services'] = $this->facilityService->getAllService();
        $result['tickets'] = $this->ticketTypeService->getTicketType();
        $result['popularTour'] = $this->facilityService->getPopularTour();
        $result['totalPages'] = $data['totalPages'];
        $result['page'] = $data['page'];
        $result['totalTours'] = $data['totalTours'];

        return $result;
    }

    public function getAll(ListTourRequest $listTourRequest): array
    {
        $result = [];
        $data = $this->tourRepository->getAllTourAdmin($listTourRequest);
        $tours = $data['tours'];
        foreach ($tours as $tour) {
            $result ['tours'][] = $this->tourTransformer->toArrayOfAdmin($tour);
        }
        $result['totalPages'] = $data['totalPages'];
        $result['page'] = $data['page'];
        $result['totalTours'] = $data['totalTours'];

        return $result;
    }

    public function getTourOfCustomer(): array
    {
        $result = [];
        $currentUser = $this->security->getUser();
        $user = $this->userRepository->find($currentUser->getId());
        $tours = $this->tourRepository->findBy(['createdUser' => $user]);
        foreach ($tours as $tour) {
            if ($tour->getDeletedAt() !== null) {
                continue;
            }
            $result[] = $this->tourTransformer->toArrayOfCustomer($tour);
        }

        return $result;
    }
}
