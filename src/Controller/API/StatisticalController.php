<?php

namespace App\Controller\API;

use App\Service\StatisticalService;

class StatisticalController
{
    public function statisticalTotalRevenue(StatisticalService $statisticalService)
    {
        return $statisticalService->statisticalTotalRevenue();
    }
}