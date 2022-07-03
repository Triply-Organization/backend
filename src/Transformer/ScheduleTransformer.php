<?php

namespace App\Transformer;

use App\Entity\Schedule;
use App\Repository\PriceListRepository;
use App\Service\PriceListService;
use Stripe\Service\PriceService;

class ScheduleTransformer
{
    private PriceListRepository $priceListRepository;
    private PriceListService $priceListService;

    public function __construct(PriceListRepository $priceListRepository, PriceListService $priceListService)
    {
        $this->priceListRepository = $priceListRepository;
        $this->priceListService = $priceListService;
    }

    public function toArray(Schedule $schedule): array
    {
        return [
            'id' => $schedule->getId(),
            'ticket' => $this->priceListService->getTicketType($schedule->getPriceLists()),
            'startDate' => $schedule->getStartDate()->format('Y-m-d')
        ];
    }
}
