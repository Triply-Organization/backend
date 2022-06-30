<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tours', name: 'api_tour_')]
class TourController extends AbstractController
{
    use ResponseTrait;

    #[isGranted('ROLE_CUSTOMER')]
    #[Route('/{id<\d+>}', name: 'delete', methods: 'DELETE')]
    public function deleteTour(Tour $tour, TourService $tourService ):JsonResponse
    {
        $tourService->delete($tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/undo/{id<\d+>}', name: 'undo_delete', methods: 'PATCH')]
    public function undoDeleteTour(Tour $tour, TourService $tourService ):JsonResponse
    {
        $tourService->undoDelete($tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
