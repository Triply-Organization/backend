<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Service\FacilityService;
use App\Service\RelatedTourService;
use App\Service\ScheduleService;
use App\Service\TicketService;
use App\Service\TourImageService;
use App\Service\TourPlanService;
use App\Service\TourService;

class TourDetailTransformer extends BaseTransformer
{
    private const PARAMS = ['id', 'title', 'duration', 'maxPeople', 'minAge', 'overView', 'price'];
    private FacilityService $facilityService;
    private TourImageService $tourImageService;
    private TourPlanService $tourPlanService;
    private ScheduleService $scheduleService;
    private RelatedTourService $relatedTourService;

    public function __construct(
        FacilityService    $facilityService,
        TourImageService   $tourImageService,
        TourPlanService    $tourPlanService,
        ScheduleService    $scheduleService,
        RelatedTourService $relatedTourService
    )
    {
        $this->facilityService = $facilityService;
        $this->tourImageService = $tourImageService;
        $this->tourPlanService = $tourPlanService;
        $this->scheduleService = $scheduleService;
        $this->relatedTourService = $relatedTourService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['schedule'] = $this->scheduleService->getPrice($tour->getSchedules()->toArray());
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourImageService->getGallary($tour);
        $result['services'] = $this->facilityService->getServices($tour->getTourServices());
        $result['tourPlans'] = $this->tourPlanService->getTourPlan($tour->getTourPlans());
        $result['relatedTour'] = $this->relatedTourService->getRelatedTour($tour->getTourPlans()->toArray(), $tour->getId());

        return $result;
    }
}
