<?php

namespace App\Controller\API;

use App\Entity\Schedule;
use App\Entity\Tour;
use App\Request\ScheduleRequest;
use App\Request\ScheduleUpdateRequest;
use App\Service\ScheduleService;
use App\Traits\ResponseTrait;
use App\Transformer\ScheduleTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/schedules', name: 'schedule_')]
class ScheduleController extends AbstractController
{
    use ResponseTrait;

    #[Route('/{id<\d+>}', name: 'add', methods: 'POST')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function addSchedule(
        Tour $tour,
        Request $request,
        ScheduleRequest $scheduleRequest,
        ValidatorInterface $validator,
        ScheduleService $scheduleService
    ): JsonResponse {
        $requestData = $request->toArray();
        $scheduleData = $scheduleRequest->fromArray($requestData);
        $checkUser = $scheduleService->checkTour($tour);
        if ($checkUser === false) {
            return $this->errors(['Something wrong']);
        }
        $errors = $validator->validate($scheduleData);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $scheduleService->addSchedule($scheduleData, $tour);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id<\d+>}', name: 'getAll', methods: 'GET')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function getScheduleOfCustomer(
        Tour $tour,
        ScheduleService $scheduleService,
        ScheduleTransformer $scheduleTransformer
    ): JsonResponse {
        $checkUser = $scheduleService->checkTour($tour);
        if ($checkUser === false) {
            return $this->errors(['Something wrong']);
        }
        $allSchedule = $scheduleService->getAllScheduleOfCustomer($tour);
        $result = $scheduleTransformer->toArrayScheduleOfCustomer($allSchedule, $tour);
        return $this->success($result);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: 'PATCH')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function updateSchedule(
        Schedule $schedule,
        Request $request,
        ScheduleUpdateRequest $scheduleUpdateRequest,
        ValidatorInterface $validator,
        ScheduleService $scheduleService
    ): JsonResponse {
        $requestData = $request->toArray();
        $scheduleData = $scheduleUpdateRequest->fromArray($requestData);
        $errors = $validator->validate($scheduleData);
        if (count($errors) > 0) {
            return $this->errors(['Something wrong']);
        }
        $scheduleService->updateSchedule($scheduleData, $schedule);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
