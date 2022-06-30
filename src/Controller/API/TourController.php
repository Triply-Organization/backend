<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Service\DestinationService;
use App\Service\FacilityService;
use App\Service\TourService;
use App\Traits\ResponseTrait;
use App\Transformer\TourDetailTransformer;
use App\Transformer\TourTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/tours', name: 'tour_')]
class TourController extends AbstractController
{
    use ResponseTrait;

    #[Route('/', name: 'lists', methods: 'GET')]
    public function getAllTours(
        Request            $request,
        ListTourRequest    $listTourRequest,
        ValidatorInterface $validator,
        TourTransformer    $tourTransformer,
        DestinationService $destinationService,
        TourService        $tourService,
        FacilityService    $facilityService
    ): JsonResponse
    {
        $query = $request->query->all();
        $tourRequest = $listTourRequest->fromArray($query);
        $errors = $validator->validate($tourRequest);
        if (count($errors) > 0) {
            return $this->errors(['Bad request']);
        }
        $tours = $tourService->findAll($tourRequest);
        $result['tours'] = $tourTransformer->listToArray($tours);
        $result['services'] = $facilityService->getAllService();
        $result['destinations'] = $destinationService->getAllDestination();

        return $this->success($result);
    }

    #[Route('/{id}', name: 'details', methods: 'GET')]
    public function tourDetails(Tour $tour, TourDetailTransformer $tourDetailTransformer): JsonResponse
    {
        return $this->success($tourDetailTransformer->toArray($tour));
    }

    #[Route('/', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function addTour(
        Request         $request,
        TourRequest     $tourRequest,
        TourService     $tourService,
        ValidatorInterface $validator,
        TourTransformer $tourTransformer,
    ): JsonResponse
    {
        $requestData = $request->toArray();
        $tour = $tourRequest->fromArray($requestData);
        $errors = $validator->validate($tour);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tourService = $tourService->addTour($tour);
        $result = $tourTransformer->toArray($tourService);

        return $this->success($result);
    }

    #[Route('/{id}', name: 'update', methods: 'PATCH')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function updateTour(
        Tour              $tour,
        Request           $request,
        TourUpdateRequest $tourUpdateRequest,
        ValidatorInterface $validator,
        TourService       $tourService,
        TourTransformer   $tourTransformer,
    ): JsonResponse
    {
        $dataRequest = $request->toArray();
        $tourUpdateRequest = $tourUpdateRequest->fromArray($dataRequest);
        $errors = $validator->validate($tour);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tourService = $tourService->updateTour($tour, $tourUpdateRequest);
        $result = $tourTransformer->toArray($tourService);

        return $this->success($result);
    }

    #[isGranted('ROLE_CUSTOMER')]
    #[Route('/{id<\d+>}', name: 'delete', methods: 'DELETE')]
    public function deleteTour(Tour $tour, TourService $tourService): JsonResponse
    {
        $tourService->delete($tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/undo/{id<\d+>}', name: 'undo_delete', methods: 'PATCH')]
    public function undoDeleteTour(Tour $tour, TourService $tourService): JsonResponse
    {
        $tourService->undoDelete($tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
