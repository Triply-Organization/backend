<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Entity\TourImage;
use App\Entity\TourPlan;
use App\Repository\TourRepository;
use App\Service\ScheduleService;
use App\Service\TourService;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private TourService $tourService;
    private ScheduleService $scheduleService;

    public function __construct(
        TourService     $tourService,
        ScheduleService $scheduleService

    )
    {
        $this->tourService = $tourService;
        $this->scheduleService = $scheduleService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourService->getCover($tour);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());

        return $result;
    }
}
