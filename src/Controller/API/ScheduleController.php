<?php

namespace App\Controller\API;

use App\Entity\Tour;
use App\Request\ScheduleRequest;
use App\Service\ScheduleService;
use App\Traits\ResponseTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    ) {
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
        return $this->success([]);
    }
}
