<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Request\TourRequest;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use App\Transformer\TourTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tours', name: 'tour_')]
class ListToursController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'lists', methods: 'GET')]
    public function index(
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
}
