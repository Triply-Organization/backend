<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Request\TourRequest;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use App\Transformer\TourTransformer;
use App\Validator\TourValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Tour;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tours', name: 'api_tour_')]
class TourController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'add')]
    public function addTour(
        Request $request,
        TourRequest $tourRequest,
        TourValidator $tourValidator,
        TourService $tourService,
        TourTransformer $tourTransformer,
    ): JsonResponse
    {
        $dataRequest = json_decode($request->getContent(), true);
        $tour = $tourRequest->fromArray($dataRequest);
        $errors = $tourValidator->validatorTourRequest($tour);
        if (!empty($errors)) {
            return $this->errors($errors);
        }
        $tourService = $tourService->addTour($tour);
        $result =$tourTransformer->fromArray($tourService);

        return $this->success($result);
    }

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
