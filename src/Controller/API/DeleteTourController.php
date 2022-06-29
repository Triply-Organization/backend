<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Service\DeleteTourService;
use App\Traits\ResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tours', name: 'api_tour_')]
class DeleteTourController extends AbstractController
{
    use ResponseTrait;

    #[Route('/{id<\d+>}', name: 'delete', methods: 'DELETE')]
    public function deleteTour(Tour $tour, DeleteTourService $tourService ):JsonResponse
    {
        $tourService->delete($tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
