<?php

namespace App\Service;

use App\Entity\PriceList;
use App\Entity\Schedule;
use App\Entity\Tour;
use App\Repository\PriceListRepository;
use App\Repository\ScheduleRepository;
use App\Repository\TourRepository;
use App\Request\ScheduleRequest;
use App\Request\ScheduleUpdateRequest;
use App\Transformer\ScheduleTransformer;
use Symfony\Component\Security\Core\Security;

class ScheduleService
{
    private ScheduleTransformer $scheduleTransformer;
    private TourRepository $tourRepository;
    private PriceListRepository $priceListRepository;
    private Security $security;
    private ScheduleRepository $scheduleRepository;
    private PriceListService $priceListService;

    public function __construct(
        ScheduleTransformer $scheduleTransformer,
        TourRepository $tourRepository,
        ScheduleRepository $scheduleRepository,
        Security $security,
        PriceListRepository $priceListRepository,
        PriceListService $priceListService
    ) {
        $this->scheduleTransformer = $scheduleTransformer;
        $this->security = $security;
        $this->scheduleRepository = $scheduleRepository;
        $this->tourRepository = $tourRepository;
        $this->priceListService = $priceListService;
        $this->priceListRepository = $priceListRepository;
    }

    public function getAllScheduleOfCustomer(Tour $tour): array
    {
        return $this->scheduleRepository->findBy(['tour' => $tour]);
    }

    public function getPrice(array $schedules): array
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

        return true;
    }

    public function updateSchedule(ScheduleUpdateRequest $scheduleUpdateRequest, Schedule $schedule)
    {
        $startDay = \DateTime::createFromFormat('Y-m-d', $scheduleUpdateRequest->getDateStart());
        $newSchedule = new Schedule();
        $newSchedule->setTour($schedule->getTour())
            ->setTicketRemain($scheduleUpdateRequest->getRemain() ?? $schedule->getTicketRemain())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setStartDate($startDay ?? $schedule->getStartDate());
        $this->scheduleRepository->add($newSchedule, true);
        $this->priceListService->updateListPrice($scheduleUpdateRequest, $newSchedule);
        foreach ($schedule->getPriceLists() as $priceList) {
            $priceList->setDeletedAt(new \DateTimeImmutable());
            $this->priceListRepository->add($priceList, true);
        }
        $schedule->setDeletedAt(new \DateTimeImmutable());
        $this->scheduleRepository->add($schedule, true);

        return true;
    }

    public function checkTour(Tour $tour): bool
    {
        $tourCheck = $this->tourRepository->find($tour);
        if (!$tourCheck) {
            return false;
        }
        $currentUser = $this->security->getUser();
        if ($currentUser->getId() === $tourCheck->getCreatedUser()->getId()) {
            return true;
        }
        return false;
    }
}
