<?php

namespace App\Controller\API;

use App\Service\StatisticalService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/statistical', name: 'statistical_')]
class StatisticalController
{
    use ResponseTrait;

    #[Route('/totalRevenue/{year<\d+>}', name: 'totalRevenue', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalTotalRevenue(StatisticalService $statisticalService, $year)
    {
        return $this->success($statisticalService->statisticalTotalRevenue($year));
    }

    #[Route('/booking/{year<\d+>}', name: 'booking', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalBooking(StatisticalService $statisticalService, $year)
    {
        return $this->success($statisticalService->statisticalBooking($year));
    }

    #[Route('/', name: 'total', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalTotal(StatisticalService $statisticalService)
    {
        return $this->success($statisticalService->statisticalTotal());
    }
}
