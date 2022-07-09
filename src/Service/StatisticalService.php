<?php

namespace App\Service;

use App\Repository\BillRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class StatisticalService
{
    private BillRepository $billRepository;
    private UserRepository $userRepository;
    private TourRepository $tourRepository;

    public function __construct(
        BillRepository $billRepository,
        UserRepository $userRepository,
        TourRepository $tourRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->billRepository = $billRepository;
        $this->tourRepository = $tourRepository;
    }

    public function statisticalTotalRevenue($year): array
    {
        return $this->billRepository->statisticalTotalRevenue($year);
    }

    public function statisticalBooking($year): array
    {
        return $this->billRepository->statisticalBooking($year);
    }
    public function statisticalTotal(): array
    {
        $totalTour = $this->tourRepository->findBy(['deletedAt' => null]);
        $overAll['overall']['totalUsers'] = count($this->userRepository->findBy(['deletedAt' => null]));
        $overAll['overall']['totalBooking'] = count($this->billRepository->findAll());
        $overAll['overall']['totalTours'] = count($totalTour);

        return $overAll;
    }
}
