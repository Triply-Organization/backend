<?php

namespace App\Service;

use App\Entity\Schedule;
use App\Entity\Tour;
use App\Repository\ScheduleRepository;
use App\Repository\TourRepository;
use App\Request\ScheduleRequest;
use App\Transformer\ScheduleTransformer;
use Symfony\Component\Security\Core\Security;

class ScheduleService
{
    private ScheduleTransformer $scheduleTransformer;
    private TourRepository $tourRepository;
    private Security $security;
    private ScheduleRepository $scheduleRepository;
    private PriceListService $priceListService;

    public function __construct(
        ScheduleTransformer $scheduleTransformer,
        TourRepository $tourRepository,
        ScheduleRepository $scheduleRepository,
        Security $security,
        PriceListService $priceListService
    ) {
        $this->scheduleTransformer = $scheduleTransformer;
        $this->security = $security;
        $this->scheduleRepository = $scheduleRepository;
        $this->tourRepository = $tourRepository;
        $this->priceListService = $priceListService;
    }

    public function getDateOpen($dates): array
    {
        $dateList = [];
        foreach ($dates as $date) {
            $dateList[] = $this->scheduleTransformer->toArray($date);
        }

        return $dateList;
    }

    public function getPrice(array $schedules)
    {
        $prices = [];
        foreach ($schedules as $schedule) {
            $prices[] = $this->scheduleTransformer->toArray($schedule);
        }

        return $prices;
    }

    public function addSchedule(ScheduleRequest $scheduleRequest, Tour $tour)
    {
        $startDay = \DateTime::createFromFormat('Y-m-d', $scheduleRequest->getDateStart());
        $schedule = new Schedule();
        $schedule->setTour($tour)
        ->setTicketRemain($scheduleRequest->getRemain())
        ->setStartDate($startDay);
        $this->scheduleRepository->add($schedule, true);
        $this->priceListService->addListPrice($scheduleRequest, $schedule);
    }

    public function checkTour(Tour $tour)
    {
        $tourCheck = $this->tourRepository->find($tour);
        if (!is_object($tourCheck)) {
            return false;
        }
        $currentUser = $this->security->getUser();
        $roles = $currentUser->getRoles();
        if ($roles['role'] !== 'ROLE_USER') {
            if ($currentUser->getId() === $tourCheck->getCreatedUser()->getId()) {
                return true;
            }
        }
        return false;
    }
}
