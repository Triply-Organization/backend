<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use App\Transformer\TourTransformer;
use App\Validator\TourValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tours', name: 'api_tour_')]
class TourController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_ADMIN')]
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
}
