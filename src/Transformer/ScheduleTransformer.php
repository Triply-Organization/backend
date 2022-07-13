<?php

namespace App\Transformer;

use App\Entity\Schedule;
use App\Entity\Tour;
use App\Repository\PriceListRepository;
use App\Service\PriceListService;
use Stripe\Service\PriceService;

class ScheduleTransformer extends BaseTransformer
{
    private PriceListService $priceListService;

    public function __construct(PriceListService $priceListService)
    {
        $this->priceListService = $priceListService;
    }

    public function toArray(Schedule $schedule): array
    {
        return [
            'id' => $schedule->getId(),
            'ticket' => $this->priceListService->getTicketType($schedule->getPriceLists()),
            'ticketRemain' => $schedule->getTicketRemain(),
            'startDate' => $schedule->getStartDate()->format('Y-m-d')
        ];
    }

    public function toArrayScheduleOfCustomer(array $allSchedule, Tour $tour)
    {
        $result = [];
        $result['idTour'] = $tour->getId();
        foreach ($allSchedule as $key => $schedule) {
            $result[$key]['id'] = $schedule->getId();
            $result[$key]['dateStart'] = $schedule->getStartDate()->format('Y-m-d');
            $result[$key]['remain'] = $schedule->getticketRemain();
            $result[$key]['ticket'] = $this->getPriceList($schedule);
        }

        return $result;
    }

    public function getPriceList(Schedule $schedule): array
    {
        $result = [];
        foreach ($schedule->getPriceLists() as $key => $priceList) {
            $result[$key]['name'] = $priceList->getType()->getName();
            $result[$key]['price'] = $priceList->getPrice();
        }

        return $result;
    }
}
