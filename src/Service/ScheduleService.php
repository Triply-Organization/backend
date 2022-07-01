<?php

namespace App\Service;

use App\Transformer\ScheduleTransformer;

class ScheduleService
{
    private ScheduleTransformer $scheduleTransformer;

    public function __construct(ScheduleTransformer $scheduleTransformer)
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

}
