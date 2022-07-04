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

    public function statisticalTotalRevenue()
    {
        $this->billRepository->statisticalTotalRevenue();
    }
}