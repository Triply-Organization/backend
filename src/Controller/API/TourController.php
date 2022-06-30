<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Request\TourRequest;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use App\Transformer\TourDetailTransformer;
use App\Transformer\TourTransformer;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use  Symfony\Component\HttpFoundation\Response;

#[Route('/api/tours', name: 'tour_')]

class TourController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'lists', methods: 'GET')]
    public function getAllTours(
        Request            $request,
        TourRequest        $tourRequest,
        ValidatorInterface $validator,
        TourTransformer    $tourTransformer,
        TourService        $tourService): JsonResponse
    {
        $query = $request->query->all();
        $tourRequest = $tourRequest->fromArray($query);
        $errors = $validator->validate($tourRequest);
        if (count($errors) > 0) {
            return $this->errors(['errors' => 'Bad request']);
        }
        $tours = $tourService->findAll($tourRequest);
        $result = $tourTransformer->listToArray($tours);

        return $this->success($result);
    }

    #[Route('/{id}', name: 'details', methods: 'GET')]
    public function tourDetails(Tour $tour, TourDetailTransformer $tourDetailTransformer): JsonResponse
    {
        return $this->success($tourDetailTransformer->toArray($tour));
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
