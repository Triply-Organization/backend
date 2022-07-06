<?php

namespace App\Controller\API;

use App\Service\StatisticalService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/statistical', name: 'statistical_')]
class StatisticalController extends AbstractController
{
    use ResponseTrait;

    #[Route('/totalRevenue/{year<\d+>}', name: 'totalRevenue', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalTotalRevenue(StatisticalService $statisticalService, $year): JsonResponse
    {
        return $this->success($statisticalService->statisticalTotalRevenue($year));
    }

    #[Route('/booking/{year<\d+>}', name: 'booking', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalBooking(StatisticalService $statisticalService, $year): JsonResponse
    {
        return $this->success($statisticalService->statisticalBooking($year));
    }

    #[Route('/', name: 'total', methods: 'GET')]
    #[IsGranted('ROLE_ADMIN')]
    public function statisticalTotal(StatisticalService $statisticalService): JsonResponse
    {
        return $this->success($statisticalService->statisticalTotal());
    }
}
