<?php

namespace App\Service;

use App\Entity\PriceList;
use App\Entity\Ticket;
use App\Entity\TicketType;
use App\Repository\PriceListRepository;
use App\Entity\Schedule;
use App\Repository\TicketRepository;
use App\Repository\TicketTypeRepository;
use App\Request\ScheduleRequest;
use App\Request\ScheduleUpdateRequest;
use App\Transformer\PriceListTransformer;

class PriceListService
{
    private PriceListRepository $priceListRepository;
    private PriceListTransformer $priceListTransformer;
    private TicketTypeRepository $ticketTypeRepository;

    public function __construct(
        PriceListRepository $priceListRepository,
        PriceListTransformer $priceListTransformer,
        TicketTypeRepository $ticketTypeRepository
    ) {
        $this->priceListRepository = $priceListRepository;
        $this->priceListTransformer = $priceListTransformer;
        $this->ticketTypeRepository = $ticketTypeRepository;
    }

    public function getTicketType($priceLists): array
    {
        $ticketType = [];
        foreach ($priceLists as $priceList) {
            $ticketType[] = $this->priceListTransformer->toArray($priceList);
        }
        return $ticketType;
    }

    public function getTicketPrice(TicketType $ticketType): ?float
    {
        $ticketPrice = $this->priceListRepository->find($ticketType);
        return $ticketPrice->getPrice();
    }

    public function addListPrice(ScheduleRequest $scheduleRequest, Schedule $schedule): bool
    {
        if ($scheduleRequest->getChildren() !== null) {
            $this->addPriceListTypeChildren($scheduleRequest, $schedule);
        }
        if ($scheduleRequest->getYouth() !== null) {
            $this->addPriceListTypeYouth($scheduleRequest, $schedule);
        }
        if ($scheduleRequest->getAdult() !== null) {
            $this->addPriceListTypeAdult($scheduleRequest, $schedule);
        }
        return true;
    }

    public function updateListPrice(ScheduleUpdateRequest $scheduleUpdateRequest, Schedule $schedule): bool
    {
        if ($scheduleUpdateRequest->getChildren() !== null) {
            $this->addPriceListTypeChildren($scheduleUpdateRequest, $schedule);
        }
        if ($scheduleUpdateRequest->getYouth() !== null) {
            $this->addPriceListTypeYouth($scheduleUpdateRequest, $schedule);
        }
        if ($scheduleUpdateRequest->getAdult() !== null) {
            $this->addPriceListTypeAdult($scheduleUpdateRequest, $schedule);
        }

        return true;
    }

    public function addPriceListTypeChildren(
        $scheduleRequest,
        Schedule $schedule
    ): bool {
        $priceList = new PriceList();
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'children']);
        if ($ticketType) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getChildren())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }

        return true;
    }

    public function addPriceListTypeYouth(
        $scheduleRequest,
        Schedule $schedule
    ): bool {
        $priceList = new PriceList();
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'youth']);
        if ($ticketType) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getYouth())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }

        return true;
    }

    public function addPriceListTypeAdult(
        $scheduleRequest,
        Schedule $schedule
    ): bool {
        $priceList = new PriceList();
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'adult']);
        if ($ticketType) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getAdult())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }

        return true;
    }
}
