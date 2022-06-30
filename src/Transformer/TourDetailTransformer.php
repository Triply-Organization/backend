<?php

namespace App\Transformer;

use App\Entity\Tour;
use App\Service\FacilityService;
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
    private TicketService $ticketService;

    public function __construct(
        FacilityService  $facilityService,
        TourImageService $tourImageService,
        TourPlanService  $tourPlanService,
        ScheduleService $scheduleService,
        TicketService  $ticketService
     )
    {
        $this->facilityService = $facilityService;
        $this->tourImageService = $tourImageService;
        $this->tourPlanService = $tourPlanService;
        $this->scheduleService = $scheduleService;
        $this->ticketService = $ticketService;
    }

    public function toArray(Tour $tour): array
    {
        $result = $this->transform($tour, static::PARAMS);
        $result['createdUser'] = $tour->getCreatedUser()->getEmail();
        $result['tourImages'] = $this->tourImageService->getGallary($tour);
        $result['tourPlans'] = $this->tourPlanService->getTourPlan($tour->getTourPlans());
        $result['services'] = $this->facilityService->getServices($tour->getServices());
        $result['tickets']  = $this->ticketService->getTicket($tour->getTickets());
        $result['dateOpen'] = $this->scheduleService->getDateOpen($tour->getSchedules());
        return $result;
    }
}
