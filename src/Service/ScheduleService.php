<?php

namespace App\Service;

use App\Entity\Schedule;
use App\Entity\Tour;
use App\Repository\PriceListRepository;
use App\Repository\ScheduleRepository;
use App\Transformer\ScheduleTransformer;

class ScheduleService
{
    private ScheduleTransformer $scheduleTransformer;

    public function __construct(ScheduleTransformer $scheduleTransformer, PriceListRepository $priceListRepository)
    {
        $this->scheduleTransformer = $scheduleTransformer;
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
}
