<?php

namespace App\Transformer;

use App\Entity\Schedule;

class ScheduleTransformer
{
    public function toArray(Schedule $schedule): array
    {
        return [
            $schedule->getStartDate()->format('Y-m-d')
        ];
    }
}
