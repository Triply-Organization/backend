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

    public function getTicketType($priceLists)
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

    public function addListPrice(ScheduleRequest $scheduleRequest, Schedule $schedule)
    {
        if ($scheduleRequest->getChildren() !== null) {
            $priceList = new PriceList();
            $this->addPriceListTypeChildren($scheduleRequest, $schedule, $priceList);
        }
        if ($scheduleRequest->getYouth() !== null) {
            $priceList = new PriceList();
            $this->addPriceListTypeYouth($scheduleRequest, $schedule, $priceList);
        }
        if ($scheduleRequest->getAdult() !== null) {
            $priceList = new PriceList();
            $this->addPriceListTypeAdult($scheduleRequest, $schedule, $priceList);
        }
    }

    private function addPriceListTypeChildren(
        ScheduleRequest $scheduleRequest,
        Schedule $schedule,
        PriceList $priceList
    ) {
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'children']);
        if (is_object($ticketType)) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getChildren())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }
    }

    private function addPriceListTypeYouth(
        ScheduleRequest $scheduleRequest,
        Schedule $schedule,
        PriceList $priceList
    ) {
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'youth']);
        if (is_object($ticketType)) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getYouth())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }
    }

    private function addPriceListTypeAdult(
        ScheduleRequest $scheduleRequest,
        Schedule $schedule,
        PriceList $priceList
    ) {
        $ticketType = $this->ticketTypeRepository->findOneBy(['name' => 'adult']);
        if (is_object($ticketType)) {
            $priceList->setType($ticketType)
                ->setPrice($scheduleRequest->getAdult())
                ->setSchedule($schedule)
                ->setCurrency('usd');
            $this->priceListRepository->add($priceList, true);
        }
    }
}
