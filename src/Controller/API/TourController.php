<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Request\ChangeStatusOfTourRequest;
use App\Request\ListTourRequest;
use App\Request\TourRequest;
use App\Request\TourUpdateRequest;
use App\Service\ListTourService;
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
        Request $request,
        ListTourRequest $listTourRequest,
        ValidatorInterface $validator,
        ListTourService $listTourService
    ): JsonResponse {
        $query = $request->query->all();
        $tourRequest = $listTourRequest->fromArray($query);
        $errors = $validator->validate($tourRequest);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tours = $listTourService->findAll($tourRequest);

        return $this->success($tours);
    }

    #[Route('/{id<\d+>}', name: 'details', methods: 'GET')]
    public function tourDetails(Tour $tour, TourDetailTransformer $tourDetailTransformer): JsonResponse
    {
        return $this->success($tourDetailTransformer->toArray($tour));
    }

    #[Route('/', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function addTour(
        Request $request,
        TourRequest $tourRequest,
        TourService $tourService,
        ValidatorInterface $validator,
        TourTransformer $tourTransformer,
    ): JsonResponse {
        $requestData = $request->toArray();
        $tour = $tourRequest->fromArray($requestData);
        $errors = $validator->validate($tour);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tourData = $tourService->addTour($tour);
        $result = $tourTransformer->toArray($tourData);

        return $this->success($result);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: 'PATCH')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function updateTour(
        Tour $tour,
        Request $request,
        TourUpdateRequest $tourUpdateRequest,
        ValidatorInterface $validator,
        TourService $tourService,
        TourTransformer $tourTransformer,
    ): JsonResponse {
        $dataRequest = $request->toArray();
        $tourUpdateRequest = $tourUpdateRequest->fromArray($dataRequest);
        $errors = $validator->validate($tour);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tourData = $tourService->updateTour($tour, $tourUpdateRequest);
        $result = $tourTransformer->toArray($tourData);

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

    #[Route('/all/', name: 'listAllTours', methods: 'GET')]
    #[isGranted('ROLE_ADMIN')]
    public function adminGetAllTours(
        Request $request,
        ListTourRequest $listTourRequest,
        ValidatorInterface $validator,
        ListTourService $listTourService
    ): JsonResponse {
        $query = $request->query->all();
        $tourRequest = $listTourRequest->fromArray($query);
        $errors = $validator->validate($tourRequest);

        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tours = $listTourService->getAll($tourRequest);

        return $this->success($tours);
    }

    #[Route('/changeStatus/{id<\d+>}', name: 'changeStatusTour ', methods: 'PATCH')]
    #[isGranted('ROLE_ADMIN')]
    public function changeStatus(
        Tour $tour,
        Request $request,
        ChangeStatusOfTourRequest $statusOfTourRequest,
        ValidatorInterface $validator,
        TourService $tourService
    ): JsonResponse {
        $dataRequest = $request->toArray();
        $statusRequest = $statusOfTourRequest->fromArray($dataRequest);
        $errors = $validator->validate($statusRequest);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $tourService->changeStatus($statusRequest, $tour);
        $result['idTour'] = $tour->getId();
        $result['status'] = $tour->getStatus();

        return $this->success($result);
    }

    #[Route('/customerTour', name: 'listTourOfCustomer', methods: 'GET')]
    #[isGranted('ROLE_CUSTOMER')]
    public function customerGetAllTours(
        ListTourService $listTourService
    ): JsonResponse {
        $tours = $listTourService->getTourOfCustomer();

        return $this->success($tours);
    }
}
