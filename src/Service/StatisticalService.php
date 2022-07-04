<?php

namespace App\Service;

use App\Repository\BillRepository;

class StatisticalService
{
    private BillRepository $billRepository;

    public function __construct(BillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    public function statisticalTotalRevenue($year)
    {
        return $this->billRepository->statisticalTotalRevenue($year);
    }

    public function statisticalBooking($year)
    {
        return $this->billRepository->statisticalBooking($year);
    }
}