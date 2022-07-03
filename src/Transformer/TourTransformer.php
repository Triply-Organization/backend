<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Service\ScheduleService;
use App\Service\TourService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TourTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private TourService $tourService;
    private ScheduleService $scheduleService;
    private ParameterBagInterface $params;

    public function __construct(
        TourService     $tourService,
        ScheduleService $scheduleService,
        ParameterBagInterface $params
    )
    {
        $this->tourService = $tourService;
        $this->scheduleService = $scheduleService;
        $this->params = $params;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->params->get('s3url') . $this->tourService->getCover($tour);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());

        return $result;
    }
}
