<?php

namespace App\Service;

use App\Repository\BillRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;

class StatisticalService
{
    private BillRepository $billRepository;
    private UserRepository $userRepository;
    private TourRepository $tourRepository;

    public function __construct(
        BillRepository $billRepository,
        UserRepository $userRepository,
        TourRepository $tourRepository
    ) {
        $this->userRepository = $userRepository;
        $this->billRepository = $billRepository;
        $this->tourRepository = $tourRepository;
    }

    public function statisticalTotalRevenue($year)
    {
        return $this->billRepository->statisticalTotalRevenue($year);
    }

    public function statisticalBooking($year)
    {
        return $this->billRepository->statisticalBooking($year);
    }
    public function statisticalTotal()
    {
        $overAll['overall']['totalUsers'] = count($this->userRepository->findAll());
        $overAll['overall']['totalBooking'] = count($this->billRepository->findAll());
        $overAll['overall']['totalTours'] = count($this->tourRepository->findAll());

        return $overAll;
    }
}
